<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory\Component;

use Boson\Contracts\Uri\Component\QueryInterface;
use Boson\Contracts\Uri\Factory\Exception\InvalidUriComponentExceptionInterface;

interface UriQueryFactoryInterface
{
    /**
     * Creates a new {@see QueryInterface} instance from
     * passed {@see string} or {@see \Stringable} argument.
     *
     * @throws InvalidUriComponentExceptionInterface in case of invalid query
     *         argument is passed.
     */
    public function createQueryFromString(string|\Stringable $query): QueryInterface;
}
