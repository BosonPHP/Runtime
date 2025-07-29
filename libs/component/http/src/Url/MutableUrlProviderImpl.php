<?php

declare(strict_types=1);

namespace Boson\Component\Http\Url;

use Boson\Contracts\Http\Url\MutableUrlProviderInterface;

/**
 * @api
 *
 * @phpstan-require-implements MutableUrlProviderInterface
 *
 * @phpstan-import-type UrlInputType from MutableUrlProviderInterface
 * @phpstan-import-type MutableUrlOutputType from MutableUrlProviderInterface
 *
 * @phpstan-ignore trait.unused
 */
trait MutableUrlProviderImpl
{
    /**
     * @var MutableUrlOutputType
     */
    public string $url {
        get => $this->url;
        /**
         * @param UrlInputType $url
         */
        set(string|\Stringable $url) => self::castMutableUrl($url);
    }

    /**
     * @param UrlInputType $url
     *
     * @return MutableUrlOutputType
     */
    public static function castMutableUrl(string|\Stringable $url): string
    {
        if (($urlScalarValue = (string) $url) === '') {
            return MutableUrlProviderInterface::DEFAULT_URL;
        }

        return $urlScalarValue;
    }
}
