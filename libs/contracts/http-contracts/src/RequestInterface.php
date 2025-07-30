<?php

declare(strict_types=1);

namespace Boson\Contracts\Http;

use Boson\Contracts\Http\Component\MethodInterface;
use Boson\Contracts\Uri\UriInterface;

/**
 * @phpstan-type MethodOutputType MethodInterface
 * @phpstan-type UrlOutputType UriInterface
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Gets HTTP method of HTTP request instance.
     *
     * @link https://httpwg.org/specs/rfc9110.html#method.definitions
     *
     * @var MethodOutputType
     */
    public MethodInterface $method {
        get;
    }

    /**
     * Gets URI instance of HTTP requestt instance.
     *
     * @var UrlOutputType
     */
    public UriInterface $url {
        get;
    }
}
