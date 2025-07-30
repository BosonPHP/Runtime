<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component;

use Boson\Component\Http\Exception\InvalidBodyComponentException;
use Boson\Contracts\Http\Factory\Component\BodyFactoryInterface;

final readonly class BodyFactory implements BodyFactoryInterface
{
    public function createBodyFromString(\Stringable|string $body): string
    {
        try {
            return (string) $body;
        /** @phpstan-ignore-next-line : PHPStan false-positive, this is not dead catch */
        } catch (\Throwable $e) {
            throw InvalidBodyComponentException::becauseStringCastingErrorOccurs($body, $e);
        }
    }
}
