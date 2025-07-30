<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Component;

use Boson\Component\Uri\Component\Scheme;
use Boson\Component\Uri\Factory\Exception\InvalidUriSchemeComponentException;
use Boson\Contracts\Uri\Component\SchemeInterface;
use Boson\Contracts\Uri\Factory\Component\UriSchemeFactoryInterface;

final readonly class UriSchemeFactory implements UriSchemeFactoryInterface
{
    public function createSchemeFromString(string|\Stringable $scheme): SchemeInterface
    {
        if ($scheme instanceof SchemeInterface) {
            return $scheme;
        }

        if ($scheme instanceof \Stringable) {
            try {
                $scalar = (string) $scheme;
            } catch (\Throwable $e) {
                throw InvalidUriSchemeComponentException::becauseStringCastingErrorOccurs($scheme, $e);
            }

            $scheme = $scalar;
        }

        if ($scheme === '') {
            throw InvalidUriSchemeComponentException::becauseUriSchemeComponentIsEmpty();
        }

        $lower = \strtolower($scheme);

        return Scheme::tryFrom($lower)
            ?? new Scheme($lower);
    }
}
