<?php

declare(strict_types=1);

namespace Boson\Component\Http;

use Boson\Component\Http\Component\BodyFactory;
use Boson\Component\Http\Component\HeadersFactory;
use Boson\Component\Http\Component\HeadersMap;
use Boson\Component\Http\Component\StatusCodeFactory;
use Boson\Contracts\Http\Component\StatusCodeInterface;
use Boson\Contracts\Http\Factory\Component\BodyFactoryInterface;
use Boson\Contracts\Http\Factory\Component\HeadersFactoryInterface;
use Boson\Contracts\Http\Factory\Component\StatusCodeFactoryInterface;
use Boson\Contracts\Http\Factory\MessageFactoryInterface;
use Boson\Contracts\Http\Factory\ResponseFactoryInterface;

/**
 * @phpstan-import-type StatusCodeInputType from ResponseFactoryInterface
 * @phpstan-import-type BodyInputType from MessageFactoryInterface
 * @phpstan-import-type HeadersInputType from MessageFactoryInterface
 */
readonly class JsonResponse extends Response
{
    /**
     * @var non-empty-lowercase-string
     */
    protected const string DEFAULT_JSON_CONTENT_TYPE = 'application/json';

    /**
     * Encode <, >, ', &, and " characters in the JSON, making
     * it also safe to be embedded into HTML.
     */
    protected const int DEFAULT_JSON_ENCODING_FLAGS = \JSON_HEX_TAG
        | \JSON_HEX_APOS
        | \JSON_HEX_AMP
        | \JSON_HEX_QUOT;

    /**
     * @param BodyInputType $body
     * @param HeadersInputType $headers
     * @param StatusCodeInputType|StatusCodeInterface $status
     *
     * @throws \JsonException
     */
    public function __construct(
        mixed $data = null,
        iterable $headers = ResponseFactoryInterface::DEFAULT_HEADERS,
        int|StatusCodeInterface $status = ResponseFactoryInterface::DEFAULT_STATUS_CODE,
        /**
         * JSON body encoding flags bit-mask.
         */
        protected int $jsonEncodingFlags = self::DEFAULT_JSON_ENCODING_FLAGS,
        BodyFactoryInterface $bodyFactory = new BodyFactory(),
        HeadersFactoryInterface $headersFactory = new HeadersFactory(),
        StatusCodeFactoryInterface $statusCodeFactory = new StatusCodeFactory(),
    ) {
        parent::__construct(
            body: $this->formatJsonBody($data),
            headers: $headers,
            status: $status,
            bodyFactory: $bodyFactory,
            headersFactory: $headersFactory,
            statusCodeFactory: $statusCodeFactory,
        );
    }

    /**
     * Extend headers by the "application/json" content type
     * in case of content-type header has not been defined.
     */
    #[\Override]
    protected function extendHeaders(HeadersMap $headers): HeadersMap
    {
        if (!$headers->has('content-type')) {
            $headers = $headers->withAddedHeader('content-type', self::DEFAULT_JSON_CONTENT_TYPE);
        }

        return parent::extendHeaders($headers);
    }

    /**
     * Encode passed data payload to a json string.
     *
     * @return BodyInputType
     * @throws \JsonException
     */
    protected function formatJsonBody(mixed $data): string|\Stringable
    {
        return \json_encode($data, $this->jsonEncodingFlags | \JSON_THROW_ON_ERROR);
    }
}
