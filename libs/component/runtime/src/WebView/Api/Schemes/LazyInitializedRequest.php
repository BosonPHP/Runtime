<?php

declare(strict_types=1);

namespace Boson\WebView\Api\Schemes;

use Boson\Component\Http\Component\HeadersMap;
use Boson\Component\Http\Request;
use Boson\Contracts\Http\Component\HeadersInterface;
use Boson\Contracts\Http\Component\MethodInterface;
use Boson\Contracts\Http\Factory\Component\BodyFactoryInterface;
use Boson\Contracts\Http\Factory\Component\HeadersFactoryInterface;
use Boson\Contracts\Http\Factory\Component\MethodFactoryInterface;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Uri\Factory\UriFactoryInterface;
use Boson\Contracts\Uri\UriInterface;
use Boson\Internal\Saucer\LibSaucer;
use FFI\CData;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\WebView\Scheme
 */
final class LazyInitializedRequest implements RequestInterface
{
    public MethodInterface $method {
        get => $this->method ??= $this->methodFactory->createMethodFromString(
            method: $this->fetchRawMethodString(),
        );
    }

    public UriInterface $url {
        get => $this->url ??= $this->uriFactory->createUriFromString(
            uri: $this->fetchRawUriString(),
        );
    }

    public HeadersInterface $headers {
        get => $this->headers ??= $this->headersFactory->createHeadersFromIterable(
            headers: $this->fetchRawHeadersIterable(),
        );
    }

    public string $body {
        get => $this->body ??= $this->bodyFactory->createBodyFromString(
            body: $this->fetchRawBodyString()
        );
    }

    public function __construct(
        private readonly LibSaucer $api,
        private readonly CData $ptr,
        private MethodFactoryInterface $methodFactory,
        private UriFactoryInterface $uriFactory,
        private HeadersFactoryInterface $headersFactory,
        private BodyFactoryInterface $bodyFactory,
    ) {}

    /**
     * @return non-empty-string
     */
    private function fetchRawMethodString(): string
    {
        $method = $this->api->saucer_scheme_request_method($this->ptr);

        try {
            $scalar = \FFI::string($method);

            if ($scalar === '') {
                return 'GET';
            }

            return $scalar;
        } finally {
            \FFI::free($method);
        }
    }

    private function fetchRawUriString(): string
    {
        $url = $this->api->saucer_scheme_request_url($this->ptr);

        try {
            return \FFI::string($url);
        } finally {
            \FFI::free($url);
        }
    }

    /**
     * @return iterable<non-empty-string, string>
     */
    private function fetchRawHeadersIterable(): iterable
    {
        $names = $this->api->new('char**');
        $values = $this->api->new('char**');
        $count = $this->api->new('size_t');

        $this->api->saucer_scheme_request_headers(
            $this->ptr,
            \FFI::addr($names),
            \FFI::addr($values),
            \FFI::addr($count),
        );

        for ($i = 0; $i < $count->cdata; ++$i) {
            /** @var CData $name */
            $name = $names[$i];

            /** @var CData $value */
            $value = $values[$i];

            $header = \FFI::string($name);

            if ($header !== '') {
                yield $header => \FFI::string($value);
            }

            $this->api->saucer_memory_free($name);
            $this->api->saucer_memory_free($value);
        }

        $this->api->saucer_memory_free($names);
        $this->api->saucer_memory_free($values);
    }

    private function fetchRawBodyString(): string
    {
        $stash = $this->api->saucer_scheme_request_content($this->ptr);

        $length = $this->api->saucer_stash_size($stash);

        try {
            if ($length <= 0) {
                return '';
            }

            $content = $this->api->saucer_stash_data($stash);

            return \FFI::string($content, $length);
        } finally {
            $this->api->saucer_stash_free($stash);
        }
    }

    public function __destruct()
    {
        $this->api->saucer_scheme_request_free($this->ptr);
    }
}
