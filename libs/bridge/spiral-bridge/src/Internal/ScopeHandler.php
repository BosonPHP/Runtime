<?php

declare(strict_types=1);


namespace Boson\Bridge\Spiral\Internal;

use Spiral\Core\Container;
use Spiral\Core\Scope;

/**
 * Exposes an ability to act in a specific Container scope without exiting the scope.
 *
 * @template TRequest
 * @template-covariant TResponse of object
 *
 * @internal
 * @psalm-internal Boson\Bridge\Spiral
 *
 * @link https://spiral.dev/docs/container-scopes
 */
final readonly class ScopeHandler
{
    private function __construct(
        private \Fiber $fiber,
    ) {}

    /**
     * Creates a new scope handler.
     *
     * @template TReq
     * @template TRes
     *
     * @param Container $container Core container.
     * @param string|\BackedEnum|null $scope Container scope name.
     * @param \Closure(mixed ...): (callable(TReq): TRes) $factory Handler factory that returns an instance of
     *        a handler. The ScopeHandler doesn't process exceptions from the handler, so you should handle them
     *        yourself.
     *
     * @return static<TReq, TRes>
     *
     * @throws \Throwable Exception from the factory.
     */
    public static function create(
        Container $container,
        string|\BackedEnum|null $scope,
        \Closure $factory,
    ): self {
        $fiber = new \Fiber(static function (
            Container $container,
            string|\BackedEnum|null $scope,
            \Closure $factory,
        ): void {
            $container->runScope(
                new Scope(
                    name: $scope,
                ),
                static function (Container $container) use ($factory): void {
                    $handler = $container->invoke($factory);
                    $response = null;
                    while (true) {
                        $request = \Fiber::suspend($response);
                        $response = $handler($request);
                    }
                },
            );
        });

        $result = new self($fiber);

        // Prepare the fiber for handling requests.
        $fiber->start($container, $scope, $factory);

        /** @var static<TReq, TRes> $result */
        return $result;
    }

    /**
     * Handles a request and returns a response.
     *
     * Sends the request to the handler and waits for a response.
     *
     * @param TRequest $request The request object.
     * @return TResponse The response object.
     *
     * @throws \Throwable If an error occurs during request handling.
     */
    public function __invoke(mixed $request): mixed
    {
        return $this->fiber->resume($request);
    }
}
