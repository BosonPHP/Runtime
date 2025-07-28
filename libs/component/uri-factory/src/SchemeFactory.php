<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory;

use Boson\Component\Uri\Component\Scheme;
use Boson\Contracts\Uri\Component\SchemeInterface;
use Boson\Contracts\Uri\Factory\SchemeFactoryInterface;

final readonly class SchemeFactory implements SchemeFactoryInterface
{
    public function createSchemeFromString(string|\Stringable $scheme): SchemeInterface
    {
        if ($scheme instanceof \Stringable) {
            $scheme = (string) $scheme;
        }

        return Scheme::tryFrom(\strtolower($scheme))
            ?? $this->createUserDefinedScheme($scheme);
    }

    /**
     * @param non-empty-string $scheme
     */
    private function createUserDefinedScheme(string $scheme): Scheme
    {
        return new Scheme($scheme);
    }
}
