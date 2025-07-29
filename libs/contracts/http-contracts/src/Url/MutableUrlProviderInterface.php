<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Url;

use Boson\Contracts\Uri\UriInterface;

/**
 * @phpstan-import-type UrlOutputType from UrlProviderInterface
 *
 * @phpstan-type UrlInputType string|\Stringable
 * @phpstan-type MutableUrlOutputType UrlOutputType
 */
interface MutableUrlProviderInterface extends UrlProviderInterface
{
    /**
     * Contains default URL definition value.
     *
     * @var UrlInputType
     */
    public const string|\Stringable DEFAULT_URL = 'about:blank';

    /**
     * Get behaviour similar to {@see UrlProviderInterface::$url}.
     *
     * @var MutableUrlOutputType
     */
    public UriInterface $url {
        get;
        /**
         * Also allows to set empty or non-normalized URL/URI string or object
         *
         * @param UrlInputType $url
         */
        set(string|\Stringable $url);
    }
}
