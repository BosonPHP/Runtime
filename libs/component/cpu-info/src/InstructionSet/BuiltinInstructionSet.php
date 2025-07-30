<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet;

use Boson\Component\CpuInfo\InstructionSetInterface;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Component\CpuInfo\InstructionSet
 */
final readonly class BuiltinInstructionSet implements InstructionSetInterface
{
    use InstructionSetImpl;
}
