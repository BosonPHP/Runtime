<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory;

use Boson\Component\CpuInfo\ArchitectureInterface;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\AVX2Detector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\AVX512FDetector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\AVXDetector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\DetectorInterface;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\FMA3Detector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\MMXDetector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\SSE2Detector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\SSE3Detector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\SSE41Detector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\SSE42Detector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\SSEDetector;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\SSSE3Detector;
use Boson\Component\CpuInfo\InstructionSetInterface;
use Boson\Component\Pasm\Executor;
use Boson\Component\Pasm\ExecutorInterface;

final readonly class CpuIdInstructionSetFactory implements InstructionSetFactoryInterface
{
    public function __construct(
        private InstructionSetFactoryInterface $delegate,
        private ExecutorInterface $executor = new Executor(),
    ) {}

    public function createInstructionSets(ArchitectureInterface $arch): array
    {
        $fallback = $this->delegate->createInstructionSets($arch);

        return \array_values(\array_unique([
            ...$fallback,
            ...$this->tryCreateFromCpuId($arch),
        ]));
    }

    /**
     * @return list<DetectorInterface>
     */
    private function getDetectors(): array
    {
        return [
            new MMXDetector(),
            new SSEDetector(),
            new SSE2Detector(),
            new SSE3Detector(),
            new SSSE3Detector(),
            new SSE41Detector(),
            new SSE42Detector(),
            new FMA3Detector(),
            new AVXDetector(),
            new AVX2Detector(),
            new AVX512FDetector(),
        ];
    }

    /**
     * @return list<InstructionSetInterface>
     */
    private function tryCreateFromCpuId(ArchitectureInterface $arch): array
    {
        $result = [];

        foreach ($this->getDetectors() as $detector) {
            if ($detector->isSupported($arch)) {
                $instructionSet = $detector->detect($this->executor);

                if ($instructionSet !== null) {
                    $result[] = $instructionSet;
                }
            }
        }

        return $result;
    }
}
