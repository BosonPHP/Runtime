<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory;

use Boson\Contracts\Uri\UriInterface;

interface UriFactoryInterface
{
    /**
     * Parse given URL/URI string to an {@see UriInterface} instance.
     */
    public function createUriFromString(string $uri): UriInterface;
}
