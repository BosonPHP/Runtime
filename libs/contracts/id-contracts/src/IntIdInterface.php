<?php

declare(strict_types=1);

namespace Boson\Contracts\Id;

use Boson\Contracts\ValueObject\IntValueObjectInterface;

/**
 * Representation of all int-like identifiers
 *
 * @template-covariant T of int = int
 *
 * @template-extends IntValueObjectInterface<T>
 */
interface IntIdInterface extends
    IntValueObjectInterface,
    IdInterface {}
