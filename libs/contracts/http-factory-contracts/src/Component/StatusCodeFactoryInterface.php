<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Factory\Component;

use Boson\Contracts\Http\Component\StatusCodeInterface;
use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;
use Boson\Contracts\Http\Factory\ResponseFactoryInterface;
use Boson\Contracts\Http\ResponseInterface;

/**
 * @phpstan-import-type StatusCodeInputType from ResponseFactoryInterface
 * @phpstan-import-type StatusCodeOutputType from ResponseInterface
 */
interface StatusCodeFactoryInterface
{
    /**
     * @param StatusCodeInputType $code
     * @param string|null $reason An optional reason phrase for **new**
     *        (non-standard) status code.
     * @return StatusCodeOutputType
     *
     * @throws InvalidMessageArgumentExceptionInterface in case of passed
     *         status code argument is invalid.
     */
    public function createStatusCode(int $code, ?string $reason = null): StatusCodeInterface;
}
