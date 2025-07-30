<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Factory;

/**
 * @phpstan-type BodyInputType string|\Stringable
 * @phpstan-type HeadersInputType iterable<non-empty-string, string|iterable<mixed, string>>
 */
interface MessageFactoryInterface
{
    /**
     * Contains default body definition value.
     *
     * @var BodyInputType
     */
    public const string|\Stringable DEFAULT_BODY = '';

    /**
     * Contains default headers list definition value.
     *
     * @var HeadersInputType
     */
    public const iterable DEFAULT_HEADERS = [];
}
