<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory\CpuId;

use Boson\Component\CpuInfo\InstructionSet;
use Boson\Component\CpuInfo\InstructionSetInterface;
use Boson\Component\Pasm\ExecutorInterface;

final readonly class AVXDetector extends AMD64Detector
{
    public function detect(ExecutorInterface $executor): ?InstructionSetInterface
    {
        $detector = $executor->compile(
            signature: 'int32_t(*)()',
            code: "\xB8\x01\x00\x00\x00"      // mov eax,0x1
                . "\x0F\xA2"                  // cpuid
                . "\xF7\xC1\x00\x00\x00\x18"  // test ecx,0x18000000 (XSAVE|OSXSAVE)
                . "\x74\x13"                  // jz no_avx
                . "\xB9\x00\x00\x00\x00"      // mov ecx,0
                . "\x0F\x01\xD0"              // xgetbv
                . "\x83\xE0\x06"              // and eax,0x6
                . "\x83\xF8\x06"              // cmp eax,0x6
                . "\x75\x06"                   // jne no_avx
                . "\xB0\x01"                   // mov al,0x1
                . "\xC3"                        // ret
                . "\x30\xC0"                   // xor al,al
                . "\xC3"                        // ret
        );

        /** @phpstan-ignore-next-line : Known ignored issue */
        return $detector() ? InstructionSet::AVX : null;
    }
}
