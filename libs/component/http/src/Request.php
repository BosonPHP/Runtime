<?php

declare(strict_types=1);

namespace Boson\Component\Http;

use Boson\Contracts\Http\Component\HeadersInterface;
use Boson\Contracts\Http\Component\MethodInterface;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Uri\UriInterface;

readonly class Request implements RequestInterface
{
    public function __construct(
        public MethodInterface $method,
        public UriInterface $url,
        public HeadersInterface $headers,
        public string $body,
    ) {}

    /**
     * Creates new request instance from another one.
     *
     * @api
     */
    public static function createFromRequest(RequestInterface $request): self
    {
        if ($request instanceof self) {
            return clone $request;
        }

        return new self(
            method: $request->method,
            url: $request->url,
            headers: $request->headers,
            body: $request->body,
        );
    }

    public function __clone(): void
    {
        /**
         * @link https://wiki.php.net/rfc/readonly_amendments
         *
         * @phpstan-ignore-next-line : PHPStan does not support PHP 8.3 clone feature
         */
        $this->headers = clone $this->headers;
    }
}
