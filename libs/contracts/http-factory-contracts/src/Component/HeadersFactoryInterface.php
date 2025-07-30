<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Factory\Component;

use Boson\Contracts\Http\Component\HeadersInterface;
use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;
use Boson\Contracts\Http\Factory\MessageFactoryInterface;
use Boson\Contracts\Http\MessageInterface;

/**
 * @phpstan-import-type HeadersInputType from MessageFactoryInterface
 * @phpstan-import-type HeadersOutputType from MessageInterface
 */
interface HeadersFactoryInterface
{
    /**
     * @param HeadersInputType $headers
     *
     * @return HeadersOutputType
     * @throws InvalidMessageArgumentExceptionInterface in case of passed
     *         headers is invalid
     */
    public function createHeadersFromIterable(iterable $headers): HeadersInterface;
}
