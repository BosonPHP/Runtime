<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Bootloader;

use Boson\Application;
use Boson\Bridge\Spiral\BosonScope;
use Boson\Bridge\Spiral\Command\StartCommand;
use Boson\Bridge\Spiral\Config\BosonConfig;
use Spiral\Boot\Bootloader\Bootloader as SpiralBootloader;
use Spiral\Core\BinderInterface;

/**
 * Registers the Boson application in the `boson` scope.
 */
final class BosonBootloader extends SpiralBootloader
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

    private function createApplication(
        BosonConfig $config,
    ): Application {
        $app = new Application(
            $config->getApplicationCreateInfo(),
        );

        $app->webview->url = $config->getInitUrl();

        return $app;
    }
}
