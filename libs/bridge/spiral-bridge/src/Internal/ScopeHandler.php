<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Internal;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Core\Container;
use Spiral\Core\Scope;

/**
 * Exposes an ability to act in a specific Container scope without exiting the scope.
 *
 * @template TRequest of ServerRequestInterface
 * @template-covariant TResponse of ResponseInterface
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Bridge\Spiral
 *
 * @link https://spiral.dev/docs/container-scopes
 */
final readonly class ScopeHandler
{
    private function __construct(
        /**
         * @var \Fiber<mixed, TRequest, void, TResponse>
         */
        private \Fiber $fiber,
    ) {}

    /**
     * Creates a new scope handler.
     *
     * @template TArgRequest of TRequest
     * @template TArgResponse of TResponse
     *
     * @param Container $container core container
     * @param string|\BackedEnum|null $scope container scope name
     * @param \Closure(mixed ...): (callable(TArgRequest): ?TArgResponse) $factory
     *        Handler factory that returns an instance of a handler.
     *        The {@see ScopeHandler} doesn't process exceptions from the
     *        handler, so you should handle them yourself.
     *
     * @return self<TArgRequest, TArgResponse>
     * @throws \Throwable exception from the factory
     */
    public static function create(
        Container $container,
        string|\BackedEnum|null $scope,
        \Closure $factory,
    ): self {
        /** @var \Fiber<mixed, TArgRequest, void, TArgResponse> $fiber */
        $fiber = new \Fiber(self::createFiberLoop(...));

        $result = new self($fiber);

        // Prepare the fiber for handling requests.
        $fiber->start($container, new Scope($scope), $factory);

        /** @var static<TArgRequest, TArgResponse> $result */
        return $result;
    }

    /**
     * Creates container-scoped fiber-based event loop
     */
    private static function createFiberLoop(Container $container, Scope $scope, \Closure $factory): void
    {
        $container->runScope($scope, static function (Container $container) use ($factory): void {
            $handler = $container->invoke($factory);
            $response = null;

            if (!\is_callable($handler)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Request handler must be a callable, but %s given',
                    \get_debug_type($handler),
                ));
            }

            /** @phpstan-ignore-next-line : PHPStan false-positive */
            while (true) {
                $request = \Fiber::suspend($response);
                $response = $handler($request);
            }
        });
    }

    /**
     * Handles a request and returns a response.
     *
     * Sends the request to the handler and waits for a response.
     *
     * @param TRequest $request the request object
     *
     * @return TResponse|null the response object
     * @throws \Throwable if an error occurs during request handling
     */
    public function __invoke(ServerRequestInterface $request): ?ResponseInterface
    {
        return $this->fiber->resume($request);
    }
}
