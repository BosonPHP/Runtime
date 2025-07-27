<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Component\Scheme;

use Boson\Contracts\Uri\Component\SchemeInterface;

/**
 * @phpstan-require-implements SchemeInterface
 */
trait SchemeImpl
{
    /**
     * @var non-empty-lowercase-string
     */
    public readonly string $name;

    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name)
    {
        /** @phpstan-ignore-next-line : The "$name" property may be defined through trait's alias */
        $this->name = \strtolower($name);
    }

    public function equals(mixed $other): bool
    {
        return $other === $this
            || ($other instanceof static
                && $other->name === $this->name);
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
