<?php

declare(strict_types=1);

namespace Boson\Component\Http;

use Boson\Component\Http\Component\BodyFactory;
use Boson\Component\Http\Component\HeadersFactory;
use Boson\Component\Http\Component\StatusCodeFactory;
use Boson\Contracts\Http\Component\StatusCodeInterface;
use Boson\Contracts\Http\Factory\Component\BodyFactoryInterface;
use Boson\Contracts\Http\Factory\Component\HeadersFactoryInterface;
use Boson\Contracts\Http\Factory\Component\StatusCodeFactoryInterface;
use Boson\Contracts\Http\Factory\ResponseFactoryInterface;
use Boson\Contracts\Http\ResponseInterface;

final readonly class ResponseFactory implements ResponseFactoryInterface
{
    public function __construct(
        private BodyFactoryInterface $bodyFactory = new BodyFactory(),
        private HeadersFactoryInterface $headersFactory = new HeadersFactory(),
        private StatusCodeFactoryInterface $statusCodeFactory = new StatusCodeFactory(),
    ) {}

    public function createRequest(
        \Stringable|string $body = self::DEFAULT_BODY,
        iterable $headers = self::DEFAULT_HEADERS,
        int|StatusCodeInterface $status = self::DEFAULT_STATUS_CODE,
    ): ResponseInterface {
        if (!$status instanceof StatusCodeInterface) {
            $status = $this->statusCodeFactory->createStatusCode($status);
        }

        return new Response(
            body: $this->bodyFactory->createBodyFromString($body),
            headers: $this->headersFactory->createHeadersFromIterable($headers),
            status: $status,
        );
    }
}
