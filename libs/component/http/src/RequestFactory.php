<?php

declare(strict_types=1);

namespace Boson\Component\Http;

use Boson\Component\Http\Component\BodyFactory;
use Boson\Component\Http\Component\HeadersFactory;
use Boson\Component\Http\Component\MethodFactory;
use Boson\Component\Http\Exception\InvalidUrlComponentException;
use Boson\Component\Uri\Factory\UriFactory;
use Boson\Contracts\Http\Factory\Component\BodyFactoryInterface;
use Boson\Contracts\Http\Factory\Component\HeadersFactoryInterface;
use Boson\Contracts\Http\Factory\Component\MethodFactoryInterface;
use Boson\Contracts\Http\Factory\RequestFactoryInterface;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Uri\Factory\Exception\InvalidUriExceptionInterface;
use Boson\Contracts\Uri\Factory\UriFactoryInterface;

final readonly class RequestFactory implements RequestFactoryInterface
{
    public function __construct(
        private MethodFactoryInterface $methodFactory = new MethodFactory(),
        private UriFactoryInterface $uriFactory = new UriFactory(),
        private HeadersFactoryInterface $headersFactory = new HeadersFactory(),
        private BodyFactoryInterface $bodyFactory = new BodyFactory(),
    ) {}

    public function createRequest(
        \Stringable|string $method = self::DEFAULT_METHOD,
        \Stringable|string $url = self::DEFAULT_URL,
        iterable $headers = self::DEFAULT_HEADERS,
        \Stringable|string $body = self::DEFAULT_BODY,
    ): RequestInterface {
        try {
            $parsedUrl = $this->uriFactory->createUriFromString($url);
        } catch (InvalidUriExceptionInterface $e) {
            throw InvalidUrlComponentException::becauseUriIsInvalid($e);
        }

        return new Request(
            method: $this->methodFactory->createMethodFromString($method),
            url: $parsedUrl,
            headers: $this->headersFactory->createHeadersFromIterable($headers),
            body: $this->bodyFactory->createBodyFromString($body),
        );
    }
}
