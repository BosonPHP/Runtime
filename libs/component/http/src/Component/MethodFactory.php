<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component;

use Boson\Component\Http\Exception\InvalidMethodComponentException;
use Boson\Contracts\Http\Component\MethodInterface;
use Boson\Contracts\Http\Factory\Component\MethodFactoryInterface;

final readonly class MethodFactory implements MethodFactoryInterface
{
    public function createMethodFromString(\Stringable|string $method): MethodInterface
    {
        if ($method instanceof MethodInterface) {
            return $method;
        }

        if ($method instanceof \Stringable) {
            try {
                $scalar = (string) $method;
            /** @phpstan-ignore-next-line : PHPStan false-positive, this is not dead catch */
            } catch (\Throwable $e) {
                throw InvalidMethodComponentException::becauseStringCastingErrorOccurs($method, $e);
            }

            $method = $scalar;
        }

        if ($method === '') {
            throw InvalidMethodComponentException::becauseMethodIsEmpty();
        }

        $uppercased = \strtoupper($method);

        return Method::tryFrom($uppercased)
            ?? new Method($uppercased);
    }
}
