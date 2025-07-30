<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Factory;

use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;
use Boson\Contracts\Http\RequestInterface;

/**
 * @phpstan-type MethodInputType non-empty-string|\Stringable
 * @phpstan-type UrlInputType string|\Stringable
 *
 * @phpstan-import-type BodyInputType from MessageFactoryInterface
 * @phpstan-import-type HeadersInputType from MessageFactoryInterface
 */
interface RequestFactoryInterface extends MessageFactoryInterface
{
    /**
     * Contains default HTTP method definition value.
     *
     * @var MethodInputType
     */
    public const string|\Stringable DEFAULT_METHOD = 'GET';

    /**
     * Contains default URL definition value.
     *
     * @var UrlInputType
     */
    public const string|\Stringable DEFAULT_URL = 'about:blank';

    /**
     * @param MethodInputType $method
     * @param UrlInputType $url
     * @param HeadersInputType $headers
     * @param BodyInputType $body
     *
     * @throws InvalidMessageArgumentExceptionInterface in case of invalid
     *         argument is passed
     */
    public function createRequest(
        string|\Stringable $method = self::DEFAULT_METHOD,
        string|\Stringable $url = self::DEFAULT_URL,
        iterable $headers = self::DEFAULT_HEADERS,
        string|\Stringable $body = self::DEFAULT_BODY,
    ): RequestInterface;
}
