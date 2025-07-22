<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory\CpuId;

use Boson\Component\CpuInfo\ArchitectureInterface;
use Boson\Component\CpuInfo\InstructionSetInterface;
use Boson\Component\Pasm\ExecutorInterface;

interface DetectorInterface
{
    public function isSupported(ArchitectureInterface $arch): bool;

    public function detect(ExecutorInterface $executor): ?InstructionSetInterface;
}
