<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver\Win32;

use FFI\CData;
use FFI\CType;

/**
 * @mixin \FFI
 *
 * @seal-properties
 * @seal-methods
 *
 * @phpstan-type VoidPtrType CData|null
 * @phpstan-type Int32PtrType CData
 */
final readonly class Kernel32
{
    /**
     * @param CType|non-empty-string $type
     */
    public function new(CType|string $type, bool $owned = true, bool $persistent = false): CData {}

    /**
     * @param CType|non-empty-string $type
     */
    public function cast(CType|string $type, CData|int|float|bool|null $ptr): CData {}

    /**
     * Reserves, commits, or changes the state of a region
     * of pages in the virtual address space of the calling process.
     *
     * Memory allocated by this function is automatically initialized to zero.
     *
     * @link https://learn.microsoft.com/en-us/windows/win32/api/memoryapi/nf-memoryapi-virtualalloc
     *
     * @param VoidPtrType $lpAddress
     * @param int<0, 4294967295> $flAllocationType
     * @param int<0, 4294967295> $flProtect
     *
     * @return VoidPtrType
     */
    public function VirtualAlloc(?CData $lpAddress, int $dwSize, int $flAllocationType, int $flProtect): ?CData {}

    /**
     * Changes the protection on a region of committed pages
     * in the virtual address space of the calling process.
     *
     * @link https://learn.microsoft.com/en-us/windows/win32/api/memoryapi/nf-memoryapi-virtualprotect
     *
     * @param VoidPtrType $lpAddress
     * @param int<0, 4294967295> $flNewProtect
     * @param Int32PtrType $lpflOldProtect
     */
    public function VirtualProtect(?CData $lpAddress, int $dwSize, int $flNewProtect, CData $lpflOldProtect): bool {}

    /**
     * Releases, decommits, or releases and decommits a region
     * of pages within the virtual address space of the calling process.
     *
     * @link https://learn.microsoft.com/en-us/windows/win32/api/memoryapi/nf-memoryapi-virtualfree
     *
     * @param VoidPtrType $lpAddress
     * @param int<0, 4294967295> $dwSize
     * @param int<0, 4294967295> $dwFreeType
     */
    public function VirtualFree(?CData $lpAddress, int $dwSize, int $dwFreeType): bool {}

    /**
     * Retrieves the calling thread's last-error code value. The last-error
     * code is maintained on a per-thread basis. Multiple threads do not
     * overwrite each other's last-error code.
     *
     * @link https://learn.microsoft.com/en-us/windows/win32/api/errhandlingapi/nf-errhandlingapi-getlasterror
     *
     * @return int<0, 4294967295>
     */
    public function GetLastError(): int {}
}
