<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory\CpuId;

use Boson\Component\CpuInfo\InstructionSet;
use Boson\Component\CpuInfo\InstructionSetInterface;
use Boson\Component\Pasm\ExecutorInterface;

final readonly class SSE3Detector extends AMD64Detector
{
    public function detect(ExecutorInterface $executor): ?InstructionSetInterface
    {
        $detector = $executor->compile(
            signature: 'int32_t(*)()',
            code: "\xB8\x01\x00\x00\x00"      // mov eax, 0x1
                . "\x0F\xA2"                  // cpuid
                . "\xF6\xC1\x01"              // test cl,0x01
                . "\x74\x05"                  // jz no_sse3
                . "\xB0\x01"                  // mov al, 0x1
                . "\xC3"                      // ret
                . "\x30\xC0"                  // xor al, al
                . "\xC3"                      // ret
        );

        /** @phpstan-ignore-next-line : Known ignored issue */
        return $detector() ? InstructionSet::SSE3 : null;
    }
}
