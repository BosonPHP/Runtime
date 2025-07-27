<?php

declare(strict_types=1);

namespace Boson\Contracts\ValueObject;

/**
 * Provides contract for system value objects.
 */
interface ValueObjectInterface extends \Stringable
{
    /**
     * Checks if the current value is equal to another value object.
     *
     * @param mixed $other the value to compare with
     */
    public function equals(mixed $other): bool;

    /**
     * Returns a string representation of the value object.
     */
    public function __toString(): string;
}
