<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Factory;

use Boson\Contracts\Http\Component\StatusCodeInterface;
use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;
use Boson\Contracts\Http\ResponseInterface;

/**
 * @phpstan-type StatusCodeInputType int
 *
 * @phpstan-import-type BodyInputType from MessageFactoryInterface
 * @phpstan-import-type HeadersInputType from MessageFactoryInterface
 */
interface ResponseFactoryInterface extends MessageFactoryInterface
{
    /**
     * Contains default status code in case of status code
     * has not been passed obviously
     *
     * @var StatusCodeInputType
     */
    public const int DEFAULT_STATUS_CODE = 200;

    /**
     * @param BodyInputType $body
     * @param HeadersInputType $headers
     * @param StatusCodeInputType|StatusCodeInterface $status
     *
     * @throws InvalidMessageArgumentExceptionInterface in case of invalid
     *         argument is passed
     */
    public function createRequest(
        string|\Stringable $body = self::DEFAULT_BODY,
        iterable $headers = self::DEFAULT_HEADERS,
        int|StatusCodeInterface $status = self::DEFAULT_STATUS_CODE,
    ): ResponseInterface;
}
