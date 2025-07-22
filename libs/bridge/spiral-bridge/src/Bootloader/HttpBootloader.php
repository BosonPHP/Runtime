<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Bootloader;

use Boson\Application;
use Boson\Bridge\Psr\Http\Psr7HttpAdapter;
use Boson\Bridge\Spiral\BosonScope;
use Boson\Bridge\Spiral\Internal\ScopeHandler;
use Boson\WebView\Api\Schemes\Event\SchemeRequestReceived;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Boot\Bootloader\Bootloader as SpiralBootloader;
use Spiral\Boot\FinalizerInterface;
use Spiral\Bootloader\Http\HttpBootloader as SpiralHttpBootloader;
use Spiral\Core\BinderInterface;
use Spiral\Core\Config\Inflector;
use Spiral\Core\Container;
use Spiral\Exceptions\ExceptionHandlerInterface;
use Spiral\Framework\Spiral;
use Spiral\Http\Http;

final class HttpBootloader extends SpiralBootloader
{
    public function defineDependencies(): array
    {
        return [
            SpiralHttpBootloader::class,
        ];
    }

    public function init(BinderInterface $binder): void
    {
        $binder
            ->getBinder(BosonScope::Boson)
            ->bind(Application::class, new Inflector($this->createApplication(...)));
    }

    private function createApplication(
        Application $app,
        ServerRequestFactoryInterface $factory,
        Container $container,
    ): Application {
        // Create PSR-7 HTTP adapter
        $psr7 = new Psr7HttpAdapter(
            requestFactory: $factory,
        );

        /**
         * @var callable(ServerRequestInterface): ResponseInterface $httpHandler
         * @phpstan-ignore-next-line : Known usage of internal enum case
         */
        $httpHandler = ScopeHandler::create($container, Spiral::Http, static fn(
                Http $http,
                ExceptionHandlerInterface $exceptionHandler,
                FinalizerInterface $finalizer,
            ) => static function (ServerRequestInterface $request) use (
                $http,
                $exceptionHandler,
                $finalizer,
            ): ?ResponseInterface {
                try {
                    return $http->handle($request);
                } catch (\Throwable $e) {
                    $exceptionHandler->report($e);

                    return null;
                } finally {
                    $finalizer->finalize();
                }
            },
        );

        // Subscribe to receive a request
        $app->on(static function (SchemeRequestReceived $e) use ($psr7, $httpHandler): void {
            $request = $psr7->createRequest($e->request);
            $response = $httpHandler($request);

            if ($response !== null) {
                $e->response = $psr7->createResponse($response);
            }
        });

        return $app;
    }
}
