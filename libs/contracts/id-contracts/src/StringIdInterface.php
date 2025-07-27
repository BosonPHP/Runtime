<?php

declare(strict_types=1);

namespace Boson\Contracts\Id;

use Boson\Contracts\ValueObject\StringValueObjectInterface;

/**
 * Representation of all string-like identifiers
 *
 * @template-extends StringValueObjectInterface<string>
 */
interface StringIdInterface extends
    StringValueObjectInterface,
    IdInterface {}
