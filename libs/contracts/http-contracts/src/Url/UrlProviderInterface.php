<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Url;

use Boson\Contracts\Uri\UriInterface;

/**
 * @phpstan-type UrlOutputType UriInterface
 */
interface UrlProviderInterface
{
    /**
     * Gets URI string of this instance.
     *
     * @var UrlOutputType
     */
    public UriInterface $url {
        get;
    }
}
