<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory;

use Boson\Contracts\Uri\Component\SchemeInterface;

interface SchemeFactoryInterface
{
    /**
     * Returns {@see SchemeInterface} instance from passed
     * non-empty {@see string} argument.
     *
     * @param non-empty-string $scheme
     */
    public function createSchemeFromString(string $scheme): SchemeInterface;
}
