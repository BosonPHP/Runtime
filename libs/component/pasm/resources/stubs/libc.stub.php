<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver\Nix;

use FFI\CData;
use FFI\CType;

/**
 * @mixin \FFI
 *
 * @seal-properties
 * @seal-methods
 *
 * @phpstan-type Int32Type int<-2147483648, 2147483647>
 * @phpstan-type SizeTType int
 * @phpstan-type OffTType int
 * @phpstan-type VoidPtrType CData|null
 */
final readonly class Libc
{
    /**
     * @param non-empty-string $library
     */
    public function __construct(
        string $library,
    ) {}

    /**
     * @param CType|non-empty-string $type
     */
    public function new(CType|string $type, bool $owned = true, bool $persistent = false): CData {}

    /**
     * @param CType|non-empty-string $type
     */
    public function cast(CType|string $type, CData|int|float|bool|null $ptr): CData {}

    /**
     * Creates a new mapping in the virtual address space of the calling
     * process. The starting address for the new mapping is specified in addr.
     * The length argument specifies the length of the mapping (which must be
     * greater than 0).
     *
     * @link https://man7.org/linux/man-pages/man2/mmap.2.html
     *
     * @param VoidPtrType $addr
     * @param SizeTType $length
     * @param Int32Type $prot
     * @param Int32Type $flags
     * @param Int32Type $fd
     * @param OffTType $offset
     *
     * @return VoidPtrType
     */
    public function mmap(?CData $addr, int $length, int $prot, int $flags, int $fd, int $offset): ?CData {}

    /**
     * Changes the access protections for the calling process's memory pages
     * containing any part of the address range in the interval
     * `[addr, addr+size-1]`. The `addr` must be aligned to a page boundary.
     *
     * @link https://man7.org/linux/man-pages/man2/mprotect.2.html
     *
     * @param VoidPtrType $addr
     * @param SizeTType $length
     * @param Int32Type $prot
     *
     * @return Int32Type
     */
    public function mprotect(?CData $addr, int $length, int $prot): int {}

    /**
     * The {@see munmap()} function shall remove any mappings for those entire
     * pages containing any part of the address space of the process
     * starting at addr and continuing for len bytes. Further references
     * to these pages shall result in the generation of a `SIGSEGV` signal
     * to the process. If there are no mappings in the specified address
     * range, then {@see munmap()} has no effect.
     *
     * @link https://man7.org/linux/man-pages/man3/munmap.3p.html
     *
     * @param VoidPtrType $addr
     * @param SizeTType $length
     *
     * @return Int32Type
     */
    public function munmap(?CData $addr, int $length): int {}
}
