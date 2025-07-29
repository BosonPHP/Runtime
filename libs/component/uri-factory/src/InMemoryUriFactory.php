<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory;

use Boson\Contracts\Uri\Factory\UriFactoryInterface;
use Boson\Contracts\Uri\UriInterface;

final class InMemoryUriFactory implements UriFactoryInterface
{
    /**
     * @var int<0, max>
     */
    public const int DEFAULT_URI_MEMORY_LIMIT = 1;

    /**
     * @var int<1, max>
     */
    public const int DEFAULT_URI_MEMORY_PITCH = 10;

    /**
     * @var array<string, UriInterface>
     */
    private array $memory = [];

    public function __construct(
        private readonly UriFactoryInterface $delegate,
        /**
         * Number of stored URI instances.
         *
         * @var int<0, max>
         */
        private readonly int $limit = self::DEFAULT_URI_MEMORY_LIMIT,
        /**
         * The number of objects upon reaching which the cleaning
         * of excess objects is triggered.
         *
         * @var int<1, max>
         */
        private readonly int $pitch = self::DEFAULT_URI_MEMORY_PITCH,
    ) {}

    public function createUriFromString(\Stringable|string $uri): UriInterface
    {
        if ($uri instanceof \Stringable) {
            $uri = (string) $uri;
        }

        return $this->memory[$uri] ??= $this->delegate->createUriFromString($uri);
    }

    private function tryCleanup(): void
    {
        if (\count($this->memory) < $this->pitch) {
            return;
        }

        $this->cleanup();
    }

    private function cleanup(): void
    {
        if ($this->limit <= 0) {
            $this->memory = [];

            return;
        }

        $this->memory = \array_slice($this->memory, 0, $this->limit);
    }
}
