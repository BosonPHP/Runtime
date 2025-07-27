<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory;

use Boson\Contracts\Uri\Component\QueryInterface;

interface QueryFactoryInterface
{
    /**
     * Creates a new {@see QueryInterface} instance from
     * passed {@see string} argument.
     */
    public function createQueryFromString(string $query): QueryInterface;
}
