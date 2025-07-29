<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory\Component;

use Boson\Contracts\Uri\Component\QueryInterface;

interface UriQueryFactoryInterface
{
    /**
     * Creates a new {@see QueryInterface} instance from
     * passed {@see string} argument.
     */
    public function createQueryFromString(string|\Stringable $query): QueryInterface;
}
