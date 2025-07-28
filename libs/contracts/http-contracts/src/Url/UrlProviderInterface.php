<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Url;

/**
 * @phpstan-type UrlOutputType non-empty-string
 */
interface UrlProviderInterface
{
    /**
     * Gets URI string of this instance.
     *
     * @var UrlOutputType
     */
    public string $url {
        get;
    }
}
