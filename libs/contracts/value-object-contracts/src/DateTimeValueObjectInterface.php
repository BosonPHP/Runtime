<?php

declare(strict_types=1);

namespace Boson\Contracts\ValueObject;

/**
 * Represents datetime value object types.
 *
 * @template-covariant T of int = int
 *
 * @template-extends IntValueObjectInterface<T>
 */
interface DateTimeValueObjectInterface extends IntValueObjectInterface
{
    /**
     * Returns inner integer timestamp value of the value object type.
     *
     * @return T
     */
    public function toTimestamp(): int;

    /**
     * Returns inner {@see DateTimeImmutable} value of the value object type.
     *
     * @return \DateTimeImmutable
     */
    public function toDateTime(): \DateTimeImmutable;
}
