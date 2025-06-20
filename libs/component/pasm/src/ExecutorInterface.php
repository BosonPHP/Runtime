<?php

declare(strict_types=1);

namespace Boson\Component\Pasm;

use Boson\Component\Pasm\Exception\NoAvailableDriverException;

/**
 * Interface for components capable of compiling assembly (machine) code
 */
interface ExecutorInterface
{
    /**
     * Compiles the given assembly code and returns an executable callable.
     *
     * ```
     * $cpuid = $executor->compile('int32_t(*)()',
     *      code: "\xB8\x01\x00\x00\x00" // mov eax, 0x1
     *          . "\x0F\xA2"             // cpuid
     *          . "\xc3"                 // ret
     * );
     *
     * $eax = $cpuid();
     *
     * echo "\nstepping: " . ($eax & 0x0F);
     * echo "\nmodel: "    . (($eax >> 4) & 0x0F);
     * echo "\nfamily: "   . (($eax >> 8) & 0x0F);
     * ```
     *
     * @param non-empty-string $signature
     * @param non-empty-string $code
     *
     * @return callable(mixed...):mixed
     *
     * @throws NoAvailableDriverException in case of no available driver found
     * @throws \Throwable in case of internal error occurs
     */
    public function compile(string $signature, string $code): callable;
}
