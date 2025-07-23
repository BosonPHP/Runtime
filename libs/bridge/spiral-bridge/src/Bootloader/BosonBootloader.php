<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Bootloader;

use Boson\Application;
use Boson\ApplicationCreateInfo;
use Boson\Bridge\Spiral\BosonScope;
use Boson\Bridge\Spiral\Command\StartCommand;
use Boson\Bridge\Spiral\Config\BosonConfig;
use Boson\WebView\WebViewCreateInfo;
use Boson\Window\WindowCreateInfo;
use Spiral\Boot\Bootloader\Bootloader as SpiralBootloader;
use Spiral\Core\BinderInterface;

/**
 * Extend this class to create your own Boson application.
 */
class BosonBootloader extends SpiralBootloader
{
    /**
     * Feel free to override this method to disable or override the dependencies.
     */
    public function defineDependencies(): array
    {
        return [
            // Console commands
            CommandBootloader::class,
            // Static file serving
            StaticBootloader::class,
            // HTTP handler
            HttpBootloader::class,
        ];
    }

    /**
     * Initializes the Boson application in the `boson` scope.
     * The `boson` scope is opened when {@see StartCommand} is executed.
     */
    public function init(BinderInterface $binder): void
    {
        $binder
            ->getBinder(BosonScope::Boson)
            ->bindSingleton(Application::class, $this->createApplication(...));
    }

    /**
     * Feel free to override this method to customize the application.
     */
    protected function createApplication(
        BosonConfig $config,
    ): Application {
        $app = new Application(
            new ApplicationCreateInfo(
                schemes: $config->getSchemes(),
                window: new WindowCreateInfo(
                    title: 'My Application',
                    webview: new WebViewCreateInfo(
                        contextMenu: true,
                    ),
                ),
            ),
        );

        $app->webview->url = $config->getInitUrl();

        return $app;
    }
}
