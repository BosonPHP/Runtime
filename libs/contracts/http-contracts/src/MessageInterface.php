<?php

declare(strict_types=1);

namespace Boson\Contracts\Http;

use Boson\Contracts\Http\Component\HeadersInterface;

/**
 * @phpstan-type HeadersOutputType HeadersInterface
 * @phpstan-type BodyOutputType string
 */
interface MessageInterface
{
    /**
     * Gets HTTP headers list of HTTP message.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc4229
     *
     * @var HeadersOutputType
     */
    public HeadersInterface $headers {
        get;
    }

    /**
     * Gets body content string of HTTP message.
     *
     * @var BodyOutputType
     */
    public string $body {
        get;
    }
}
