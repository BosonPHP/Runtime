<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Method;

/**
 * @phpstan-type MethodOutputType non-empty-uppercase-string
 */
interface MethodProviderInterface
{
    /**
     * Gets HTTP method of this instance.
     *
     * @link https://httpwg.org/specs/rfc9110.html#method.definitions
     *
     * @var MethodOutputType
     */
    public string $method {
        get;
    }
}
