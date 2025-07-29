<?php

declare(strict_types=1);

namespace Boson\Component\GlobalsProvider;

use Boson\Contracts\Http\RequestInterface;
use Psr\Clock\ClockInterface;

/**
 * Returns basic request-aware parameters.
 */
final readonly class DefaultServerGlobalsProvider implements ServerGlobalsProviderInterface
{
    /**
     * @var non-empty-uppercase-string
     */
    private const string UPPER = '_ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var non-empty-lowercase-string
     */
    private const string LOWER = '-abcdefghijklmnopqrstuvwxyz';

    public function __construct(
        /**
         * Allows you to set time-dependent parameters for server global values.
         *
         * If the value is not set (defined as {@see null}), the system
         * time will be used.
         */
        private ?ClockInterface $clock = null,
    ) {}

    public function getServerGlobals(RequestInterface $request): array
    {
        return [
            ...$this->getRequestTime(),
            ...$this->getRequestInfo($request),
            ...$this->getRequestHeaders($request),
        ];
    }

    /**
     * @return array<non-empty-uppercase-string, string|int>
     */
    private function getRequestInfo(RequestInterface $request): array
    {
        $query = $request->url->query->toString();
        $path = $request->url->path->toString();
        $host = $request->url->authority->host ?? '127.0.0.1';
        $port = $request->url->authority->port ?? 80;

        if ($path === '') {
            $path = '/';
        }

        return [
            'REQUEST_METHOD' => $request->method,
            'QUERY_STRING' => $query,
            'PATH_INFO' => $path,
            'REMOTE_ADDR' => $host,
            'REMOTE_PORT' => $port,
            // compound parameters
            'REQUEST_URI' => $path . ($query === '' ? '' : '?' . $query),
            'HTTP_HOST' => $host . ':' . $port,
        ];
    }

    /**
     * @return array<non-empty-uppercase-string, string>
     */
    private function getRequestHeaders(RequestInterface $request): array
    {
        $result = [];

        foreach ($request->headers as $name => $value) {
            $result['HTTP_' . \strtr($name, self::LOWER, self::UPPER)] = $value;
        }

        /** @var array<non-empty-uppercase-string, string> */
        return $result;
    }

    /**
     * @return array{
     *     REQUEST_TIME_FLOAT: float,
     *     REQUEST_TIME: int,
     *     ...
     * }
     */
    private function getRequestTime(): array
    {
        if ($this->clock !== null) {
            return $this->getRequestTimeFromPsr20($this->clock);
        }

        $microtime = \microtime(true);

        return [
            'REQUEST_TIME_FLOAT' => $microtime,
            'REQUEST_TIME' => (int) $microtime,
        ];
    }

    /**
     * @return array{
     *     REQUEST_TIME_FLOAT: float,
     *     REQUEST_TIME: int,
     *     ...
     * }
     */
    private function getRequestTimeFromPsr20(ClockInterface $clock): array
    {
        $now = $clock->now();
        $microtime = $now->getTimestamp();

        return [
            'REQUEST_TIME_FLOAT' => $microtime + .000_001 * $now->getMicrosecond(),
            'REQUEST_TIME' => $microtime,
        ];
    }
}
