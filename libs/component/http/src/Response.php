<?php

declare(strict_types=1);

namespace Boson\Component\Http;

use Boson\Component\Http\Component\BodyFactory;
use Boson\Component\Http\Component\HeadersFactory;
use Boson\Component\Http\Component\HeadersMap;
use Boson\Component\Http\Component\StatusCodeFactory;
use Boson\Contracts\Http\Component\HeadersInterface;
use Boson\Contracts\Http\Component\StatusCodeInterface;
use Boson\Contracts\Http\Factory\Component\BodyFactoryInterface;
use Boson\Contracts\Http\Factory\Component\HeadersFactoryInterface;
use Boson\Contracts\Http\Factory\Component\StatusCodeFactoryInterface;
use Boson\Contracts\Http\Factory\MessageFactoryInterface;
use Boson\Contracts\Http\Factory\ResponseFactoryInterface;
use Boson\Contracts\Http\MessageInterface;
use Boson\Contracts\Http\ResponseInterface;

/**
 * @phpstan-import-type StatusCodeInputType from ResponseFactoryInterface
 * @phpstan-import-type StatusCodeOutputType from ResponseInterface
 * @phpstan-import-type BodyInputType from MessageFactoryInterface
 * @phpstan-import-type BodyOutputType from MessageInterface
 * @phpstan-import-type HeadersInputType from MessageFactoryInterface
 * @phpstan-import-type HeadersOutputType from MessageInterface
 */
readonly class Response implements ResponseInterface
{
    public StatusCodeInterface $status;

    public HeadersInterface $headers;

    public string $body;

    /**
     * @param BodyInputType $body
     * @param HeadersInputType $headers
     * @param StatusCodeInputType|StatusCodeInterface $status
     */
    public function __construct(
        string|\Stringable $body = ResponseFactoryInterface::DEFAULT_BODY,
        iterable $headers = ResponseFactoryInterface::DEFAULT_HEADERS,
        int|StatusCodeInterface $status = ResponseFactoryInterface::DEFAULT_STATUS_CODE,
        BodyFactoryInterface $bodyFactory = new BodyFactory(),
        HeadersFactoryInterface $headersFactory = new HeadersFactory(),
        StatusCodeFactoryInterface $statusCodeFactory = new StatusCodeFactory(),
    ) {
        $this->body = $this->createBody($bodyFactory, $body);
        $this->headers = $this->createHeaders($headersFactory, $headers);
        $this->status = $this->createCode($statusCodeFactory, $status);
    }

    /**
     * @param BodyInputType $body
     * @return BodyOutputType
     */
    private function createBody(BodyFactoryInterface $factory, string|\Stringable $body): string
    {
        return $factory->createBodyFromString($body);
    }

    /**
     * @param HeadersInputType $headers
     * @return HeadersOutputType
     */
    private function createHeaders(HeadersFactoryInterface $factory, iterable $headers): HeadersInterface
    {
        $map = $factory->createHeadersFromIterable($headers);

        if (!$map instanceof HeadersMap) {
            $map = HeadersMap::createFromHeaders($map);
        }

        return $this->extendHeaders($map);
    }

    protected function extendHeaders(HeadersMap $headers): HeadersMap
    {
        // Set UTF-8 text/html content header in case of
        // content-type header line is not defined.
        if (!$headers->has('content-type')) {
            $headers = $headers->withAddedHeader('content-type', 'text/html; charset=utf-8');
        }

        // Fix unnecessary content-length.
        if ($headers->has('transfer-encoding')) {
            $headers = $headers->withoutHeader('content-length');
        }

        return $headers;
    }

    /**
     * @param StatusCodeInputType|StatusCodeInterface $code
     * @return StatusCodeOutputType
     */
    private function createCode(StatusCodeFactoryInterface $factory, int|StatusCodeInterface $code): StatusCodeInterface
    {
        if ($code instanceof StatusCodeInterface) {
            return $code;
        }

        return $factory->createStatusCode($code);
    }

    /**
     * Creates new response instance from another one.
     *
     * @api
     */
    public static function createFromResponse(ResponseInterface $response): self
    {
        if ($response instanceof self) {
            return clone $response;
        }

        return new self(
            body: $response->body,
            headers: $response->headers,
            status: $response->status,
        );
    }
}
