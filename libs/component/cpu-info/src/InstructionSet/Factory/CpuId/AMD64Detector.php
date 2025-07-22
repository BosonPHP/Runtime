<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory\CpuId;

use Boson\Component\CpuInfo\Architecture;
use Boson\Component\CpuInfo\ArchitectureInterface;

abstract readonly class AMD64Detector implements DetectorInterface
{
    public function isSupported(ArchitectureInterface $arch): bool
    {
        return $arch->is(Architecture::Amd64);
    }
}
