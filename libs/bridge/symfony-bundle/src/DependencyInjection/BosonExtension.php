<?php

declare(strict_types=1);

namespace Boson\Bridge\Symfony\DependencyInjection;

use Boson\Application;
use Boson\ApplicationCreateInfo;
use Boson\Bridge\Symfony\Http\SymfonyHttpAdapter;
use Boson\Component\Compiler\Command\CompileCommand;
use Boson\Component\Compiler\Command\InitCommand;
use Boson\Component\Compiler\Command\PackCommand;
use Boson\Component\GlobalsProvider\CompoundServerGlobalsProvider;
use Boson\Component\GlobalsProvider\DefaultServerGlobalsProvider;
use Boson\Component\GlobalsProvider\ServerGlobalsProviderInterface;
use Boson\Component\GlobalsProvider\StaticServerGlobalsProvider;
use Boson\Component\Http\Body\BodyDecoderFactory;
use Boson\Component\Http\Body\BodyDecoderInterface;
use Boson\Component\Http\Body\FormUrlEncodedDecoder;
use Boson\Component\Http\Body\MultipartFormDataDecoder;
use Boson\Component\Http\Static\FilesystemStaticProvider;
use Boson\Component\Http\Static\Mime\ExtensionMimeTypeDetector;
use Boson\Component\Http\Static\Mime\MimeTypeDetectorInterface;
use Boson\Component\Http\Static\StaticProviderInterface;
use Boson\WebView\WebViewCreateInfo;
use Boson\Window\WindowCreateInfo;
use Boson\Window\WindowDecoration;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @phpstan-import-type BosonConfigType from BosonConfiguration
 */
final class BosonExtension extends Extension
{
    /**
     * @param array<array-key, mixed> $config
     */
    public function getConfiguration(array $config, ContainerBuilder $container): BosonConfiguration
    {
        return new BosonConfiguration();
    }

    /**
     * @param array<array-key, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @var BosonConfigType $configs */
        $configs = $this->processConfiguration(new BosonConfiguration(), $configs);

        $this->registerSymfonyHttpServices($container);
        $this->registerSymfonyStaticServices($configs, $container);
        $this->registerApplicationServices($configs, $container);

        $this->registerParameters($configs, $container);

        $this->shareServices($container);

        $this->registerConsoleCommands($container);
    }

    private function registerConsoleCommands(ContainerBuilder $container): void
    {
        if (\class_exists(InitCommand::class)) {
            $container->register(InitCommand::class, InitCommand::class)
                ->setArgument('$name', 'boson:init')
                ->addTag('console.command');
        }

        if (\class_exists(CompileCommand::class)) {
            $container->register(CompileCommand::class, CompileCommand::class)
                ->setArgument('$name', 'boson:compile')
                ->addTag('console.command');
        }

        if (\class_exists(PackCommand::class)) {
            $container->register(PackCommand::class, PackCommand::class)
                ->setArgument('$name', 'boson:pack')
                ->addTag('console.command');
        }
    }

    private function shareServices(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(StaticProviderInterface::class)) {
            $container->getDefinition(StaticProviderInterface::class)
                ->setPublic(true);
        }

        if ($container->hasAlias(StaticProviderInterface::class)) {
            $container->getAlias(StaticProviderInterface::class)
                ->setPublic(true);
        }
    }

    /**
     * @param BosonConfigType $config
     */
    private function registerParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('boson.entrypoint', $config['window']['entrypoint']);
    }

    private function registerMimeDetectorServices(ContainerBuilder $container): void
    {
        $container->register(ExtensionMimeTypeDetector::class, ExtensionMimeTypeDetector::class)
            ->setArgument('$delegate', null)
            ->setAutowired(true);

        $container->setAlias(MimeTypeDetectorInterface::class, ExtensionMimeTypeDetector::class);
    }

    /**
     * @param BosonConfigType $config
     */
    private function registerSymfonyStaticServices(array $config, ContainerBuilder $container): void
    {
        $this->registerMimeDetectorServices($container);

        $container->register(FilesystemStaticProvider::class, FilesystemStaticProvider::class)
            ->setArgument('$root', $config['static']['directory'] ?? [])
            ->setArgument('$mimeTypeDetector', new Reference(MimeTypeDetectorInterface::class))
            ->setAutowired(true);

        if ($container->has(StaticProviderInterface::class)) {
            return;
        }

        $container->setAlias(StaticProviderInterface::class, FilesystemStaticProvider::class);
    }

    /**
     * @param BosonConfigType $config
     */
    private function registerApplicationServices(array $config, ContainerBuilder $container): void
    {
        $this->registerApplicationConfigServices($config, $container);

        $container->register(Application::class, Application::class)
            ->setArgument('$info', new Reference(ApplicationCreateInfo::class))
            ->setArgument('$dispatcher', new Reference(EventDispatcherInterface::class))
            ->setPublic(true)
            ->setAutowired(true);
    }

    /**
     * @param BosonConfigType $config
     */
    private function registerApplicationConfigServices(array $config, ContainerBuilder $container): void
    {
        $container->register(WebViewCreateInfo::class, WebViewCreateInfo::class)
            ->setArgument('$storage', $config['window']['storage'])
            ->setArgument('$flags', $config['window']['flags'])
            ->setArgument('$contextMenu', $config['window']['enable_context_menu'])
            ->setArgument('$devTools', $config['window']['enable_dev_tools'])
            ->setAutowired(true);

        $container->register(WindowCreateInfo::class, WindowCreateInfo::class)
            ->setArgument('$title', $config['name'])
            ->setArgument('$width', $config['window']['width'])
            ->setArgument('$height', $config['window']['height'])
            ->setArgument('$visible', $config['window']['is_visible'])
            ->setArgument('$resizable', $config['window']['is_resizable'])
            ->setArgument('$alwaysOnTop', $config['window']['is_always_on_top'])
            ->setArgument('$clickThrough', $config['window']['is_click_through'])
            ->setArgument('$decoration', match ($config['window']['decorations']) {
                'dark_mode' => WindowDecoration::DarkMode,
                'frameless' => WindowDecoration::Frameless,
                'transparent' => WindowDecoration::Transparent,
                default => WindowDecoration::Default,
            })
            ->setAutowired(true);

        $container->register(ApplicationCreateInfo::class, ApplicationCreateInfo::class)
            ->setArgument('$name', $config['name'])
            ->setArgument('$schemes', $config['schemes'])
            ->setArgument('$debug', $config['is_debug'])
            ->setArgument('$quitOnClose', $config['is_quit_on_close'])
            ->setArgument('$autorun', false)
            ->setAutowired(true);
    }

    private function registerSymfonyHttpServices(ContainerBuilder $container): void
    {
        $this->registerServerGlobalsServices($container);
        $this->registerBodyDecoderServices($container);

        $container->register(SymfonyHttpAdapter::class, SymfonyHttpAdapter::class)
            ->setArgument('$server', new Reference(ServerGlobalsProviderInterface::class))
            ->setArgument('$body', new Reference(BodyDecoderInterface::class))
            ->setPublic(true)
            ->setAutowired(true);
    }

    private function registerBodyDecoderServices(ContainerBuilder $container): void
    {
        $container->register(FormUrlEncodedDecoder::class, FormUrlEncodedDecoder::class)
            ->addTag('boson.http.body_decoder')
            ->setAutowired(true);

        $container->register(MultipartFormDataDecoder::class, MultipartFormDataDecoder::class)
            ->addTag('boson.http.body_decoder')
            ->setAutowired(true);

        $container->register(BodyDecoderFactory::class, BodyDecoderFactory::class)
            ->setArgument('$decoders', new TaggedIteratorArgument('boson.http.body_decoder'))
            ->setAutowired(true);

        if ($container->has(BodyDecoderInterface::class)) {
            return;
        }

        $container->setAlias(BodyDecoderInterface::class, BodyDecoderFactory::class);
    }

    private function registerServerGlobalsServices(ContainerBuilder $container): void
    {
        $container->register(StaticServerGlobalsProvider::class, StaticServerGlobalsProvider::class)
            ->addTag('boson.globals.server')
            ->setAutowired(true);

        $container->register(DefaultServerGlobalsProvider::class, DefaultServerGlobalsProvider::class)
            ->addTag('boson.globals.server')
            ->setAutowired(true);

        $container->register(CompoundServerGlobalsProvider::class, CompoundServerGlobalsProvider::class)
            ->setArgument('$providers', new TaggedIteratorArgument('boson.globals.server'))
            ->setAutowired(true);

        if ($container->has(ServerGlobalsProviderInterface::class)) {
            return;
        }

        $container->setAlias(ServerGlobalsProviderInterface::class, CompoundServerGlobalsProvider::class);
    }
}
