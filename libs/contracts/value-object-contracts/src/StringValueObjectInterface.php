<?php

declare(strict_types=1);

namespace Boson\Contracts\ValueObject;

/**
 * Represents string value object types.
 *
 * @template-covariant T of string = string
 *
 * @template-extends ValueObjectInterface<T>
 */
interface StringValueObjectInterface extends ValueObjectInterface
{
    /**
     * Returns inner string value of the value object type.
     *
     * Note: The method is semantically different from {@see __toString()}.
     *       - The {@see toString()} returns the internal state in the form
     *         of a {@see string} scalar, which can be further used
     *         for automation.
     *       - The {@see __toString()} method returns a string representation
     *         (visualization) of the data.
     *
     * @return T
     */
    public function toString(): string;
}
