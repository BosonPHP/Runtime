<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Bootloader;

use Boson\Application;
use Boson\Bridge\Spiral\BosonScope;
use Boson\Bridge\Spiral\Config\BosonConfig;
use Boson\Component\Http\Static\FilesystemStaticProvider;
use Boson\Component\Http\Static\Mime\ExtensionMimeTypeDetector;
use Boson\Component\Http\Static\Mime\MimeTypeDetectorInterface;
use Boson\Component\Http\Static\StaticProviderInterface;
use Boson\WebView\Api\Schemes\Event\SchemeRequestReceived;
use Spiral\Boot\Bootloader\Bootloader as SpiralBootloader;
use Spiral\Core\BinderInterface;
use Spiral\Core\Config\Inflector;

final class StaticBootloader extends SpiralBootloader
{
    /**
     * Temporary flag to prevent multiple listener registration.
     */
    private bool $registered = false;

    public function init(BinderInterface $binder): void
    {
        $binder = $binder->getBinder(BosonScope::Boson);

        $binder->bindSingleton(StaticProviderInterface::class, FilesystemStaticProvider::class);
        $binder->bindSingleton(MimeTypeDetectorInterface::class, ExtensionMimeTypeDetector::class);

        $binder->bind(Application::class, new Inflector($this->registerHandler(...)));
        $binder->bindSingleton(
            FilesystemStaticProvider::class,
            static fn(BosonConfig $config): FilesystemStaticProvider => new FilesystemStaticProvider(
                $config->getStaticFiles(),
            ),
        );
    }

    private function registerHandler(
        Application $app,
        StaticProviderInterface $static,
    ): Application {
        if ($this->registered) {
            return $app;
        }

        $this->registered = true;

        $app->addEventListener(
            SchemeRequestReceived::class,
            function (SchemeRequestReceived $e) use ($static): void {
                $staticBosonResponse = $static->findFileByRequest($e->request);

                if ($staticBosonResponse !== null) {
                    $e->response = $staticBosonResponse;
                    $e->stopPropagation();
                }
            },
        );

        return $app;
    }
}
