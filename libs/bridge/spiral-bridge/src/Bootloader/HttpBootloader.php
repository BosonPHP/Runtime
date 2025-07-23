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
    /**
     * Temporary flag to prevent multiple listener registration.
     */
    private bool $registered = false;

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
            ->bind(Application::class, new Inflector($this->registerHandler(...)));
    }

    private function registerHandler(
        Application $app,
        ServerRequestFactoryInterface $factory,
        Container $container,
    ): Application {
        if ($this->registered) {
            return $app;
        }

        $this->registered = true;
        // Create PSR-7 HTTP adapter
        $psr7 = new Psr7HttpAdapter(
            requestFactory: $factory,
        );

        /** @phpstan-ignore-next-line : Known usage of internal enum case */
        $scope = Spiral::Http;

        $httpHandler = ScopeHandler::create(
            $container,
            $scope,
            self::handlerFactory(...),
        );

        // Subscribe to receive a request
        $app->addEventListener(
            SchemeRequestReceived::class,
            static function (SchemeRequestReceived $e) use ($psr7, $httpHandler): void {
                $request = $psr7->createRequest($e->request);
                $response = $httpHandler($request);

                if ($response !== null) {
                    $e->response = $psr7->createResponse($response);
                }
            },
        );

        return $app;
    }

    /**
     * Creates a handler for the HTTP requests.
     *
     * @return \Closure(ServerRequestInterface): (ResponseInterface|null)
     */
    private static function handlerFactory(
        Http $http,
        ExceptionHandlerInterface $exceptionHandler,
        FinalizerInterface $finalizer,
    ): \Closure {
        return static function (ServerRequestInterface $request) use (
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
        };
    }
}
