<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory;

use Boson\Component\CpuInfo\ArchitectureInterface;
use Boson\Component\CpuInfo\InstructionSetInterface;

final class GenericInstructionSetFactory implements InstructionSetFactoryInterface
{
    /**
     * @return list<InstructionSetInterface>
     */
    public function createInstructionSets(ArchitectureInterface $arch): array
    {
        return [];
    }
}
