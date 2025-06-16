<?php

declare(strict_types=1);

namespace Boson\Bridge\Symfony\DependencyInjection;

use Boson\Application;
use Boson\ApplicationCreateInfo;
use Boson\Bridge\Symfony\Http\SymfonyHttpAdapter;
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
        $this->registerSymfonyStaticServices($container);
        $this->registerApplicationServices($configs, $container);

        $this->registerParameters($configs, $container);
    }

    /**
     * @param BosonConfigType $config
     */
    private function registerParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('boson.entrypoint', $config['entrypoint']);
    }

    private function registerMimeDetectorServices(ContainerBuilder $container): void
    {
        $container->register(ExtensionMimeTypeDetector::class, ExtensionMimeTypeDetector::class)
            ->setArgument('$delegate', null)
            ->setAutowired(true);

        $container->setAlias(MimeTypeDetectorInterface::class, ExtensionMimeTypeDetector::class);
    }

    private function registerSymfonyStaticServices(ContainerBuilder $container): void
    {
        $this->registerMimeDetectorServices($container);

        $container->register(FilesystemStaticProvider::class, FilesystemStaticProvider::class)
            ->setArgument('$root', ['%kernel.project_dir%/public'])
            ->setArgument('$mimeTypeDetector', new Reference(MimeTypeDetectorInterface::class))
            ->setAutowired(true);

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
            ->setAutowired(true);

        $container->register(WindowCreateInfo::class, WindowCreateInfo::class)
            ->setArgument('$title', $config['name'])
            ->setArgument('$width', $config['width'])
            ->setArgument('$height', $config['height'])
            ->setAutowired(true);

        $container->register(ApplicationCreateInfo::class, ApplicationCreateInfo::class)
            ->setArgument('$name', $config['name'])
            ->setArgument('$schemes', $config['schemes'])
            ->setArgument('$debug', $config['debug'])
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

        $container->setAlias(ServerGlobalsProviderInterface::class, CompoundServerGlobalsProvider::class);
    }
}
