<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Factory\Component;

use Boson\Contracts\Http\Component\MethodInterface;
use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;
use Boson\Contracts\Http\Factory\RequestFactoryInterface;
use Boson\Contracts\Http\RequestInterface;

/**
 * @phpstan-import-type MethodInputType from RequestFactoryInterface
 * @phpstan-import-type MethodOutputType from RequestInterface
 */
interface MethodFactoryInterface
{
    /**
     * @param MethodInputType $method
     *
     * @return MethodOutputType
     * @throws InvalidMessageArgumentExceptionInterface in case of passed
     *         method is invalid
     */
    public function createMethodFromString(string|\Stringable $method): MethodInterface;
}
