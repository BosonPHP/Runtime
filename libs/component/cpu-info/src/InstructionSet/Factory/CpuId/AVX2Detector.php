<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory\CpuId;

use Boson\Component\CpuInfo\InstructionSet;
use Boson\Component\CpuInfo\InstructionSetInterface;
use Boson\Component\Pasm\ExecutorInterface;

final readonly class AVX2Detector extends AMD64Detector
{
    public function detect(ExecutorInterface $executor): ?InstructionSetInterface
    {
        $detector = $executor->compile(
            signature: 'int32_t(*)()',
            code: "\xb8\x07\x00\x00\x00" // mov eax, 7
                . "\x31\xc9"             // xor ecx, ecx
                . "\x0f\xa2"             // cpuid
                . "\x0f\xba\xf3\x05"     // bt ebx, 5
                . "\x0f\x92\xc0"         // setc al
                . "\x0f\xb6\xc0"         // movzx eax, al
                . "\xc3"                 // ret
        );

        /** @phpstan-ignore-next-line : Known ignored issue */
        return $detector() ? InstructionSet::AVX2 : null;
    }
}
