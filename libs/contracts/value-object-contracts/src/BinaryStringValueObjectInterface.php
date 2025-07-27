<?php

declare(strict_types=1);

namespace Boson\Contracts\ValueObject;

/**
 * Represents binary string value object types.
 *
 * @template-covariant T of string = string
 *
 * @template-extends StringValueObjectInterface<T>
 */
interface BinaryStringValueObjectInterface extends StringValueObjectInterface
{
    /**
     * Returns inner binary string timestamp value of the value object type.
     *
     * @return T
     */
    public function toBytes(): string;
}
