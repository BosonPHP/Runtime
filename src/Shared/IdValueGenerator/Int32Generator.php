<?php

declare(strict_types=1);

namespace Serafim\Boson\Shared\IdValueGenerator;

/**
 * The most compatible generator with all subsystems and platforms.
 *
 * @template-extends IntValueGenerator<int<0, 2147483647>>
 */
final class Int32Generator extends IntValueGenerator
{
    public readonly int $initial;

    public readonly int $maximum;

    /**
     * @param (OverflowBehaviour::*) $onOverflow
     */
    public function __construct(
        OverflowBehaviour $onOverflow = OverflowBehaviour::Reset,
    ) {
        $this->initial = 0;
        $this->maximum = 0x7FFF_FFFF;

        parent::__construct($onOverflow);
    }
}
