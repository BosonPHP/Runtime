<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory;

use Boson\Component\CpuInfo\ArchitectureInterface;

final class DefaultInstructionSetFactory implements InstructionSetFactoryInterface
{
    private InstructionSetFactoryInterface $default;

    public function __construct()
    {
        $this->default = new CompoundInstructionSetFactory(
            default: new GenericInstructionSetFactory(),
            factories: [
                new LinuxProcCpuInfoInstructionSetFactory(),
                new CpuIdInstructionSetFactory(),
            ],
        );
    }

    public function createInstructionSets(ArchitectureInterface $arch): array
    {
        return $this->default->createInstructionSets($arch);
    }
}
