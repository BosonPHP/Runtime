<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory\CpuId;

use Boson\Component\CpuInfo\InstructionSet;
use Boson\Component\CpuInfo\InstructionSetInterface;
use Boson\Component\Pasm\ExecutorInterface;

final readonly class MMXDetector extends AMD64Detector
{
    public function detect(ExecutorInterface $executor): ?InstructionSetInterface
    {
        $detector = $executor->compile(
            signature: 'int32_t(*)()',
            code: "\xB8\x01\x00\x00\x00"     // mov eax, 0x1
                . "\x0F\xA2"                 // cpuid
                . "\xF7\xC2\x00\x00\x80\x00" // test edx, 0x00800000 (1 << 23)
                . "\x0F\x94\xC0"             // setz al
                . "\x34\x01"                 // xor al, 1
                . "\xC3"                     // ret
        );

        /** @phpstan-ignore-next-line : Known ignored issue */
        return $detector() ? InstructionSet::MMX : null;
    }
}
