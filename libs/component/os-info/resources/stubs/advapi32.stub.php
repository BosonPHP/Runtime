<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory\Win32;

use FFI\CData;
use FFI\CType;

/**
 * @mixin \FFI
 * @seal-properties
 * @seal-methods
 *
 * @phpstan-type LongStatusType int<-2147483648, 2147483647>
 * @phpstan-type HKeyType CData
 * @phpstan-type UInt32PointerType CData
 * @phpstan-type VoidPointerType CData
 */
final readonly class Advapi32
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
     * @param HKeyType|null $hKey
     * @param int<0, 4294967295> $dwFlags
     * @param UInt32PointerType|null $pdwType
     * @param VoidPointerType|null $pvData
     * @param UInt32PointerType|null $pcbData
     *
     * @return int<-2147483648, 2147483647>
     */
    public function RegGetValueA(
        ?CData $hKey,
        string $lpSubKey,
        string $lpValue,
        int $dwFlags,
        ?CData $pdwType,
        ?CData $pvData,
        ?CData $pcbData,
    ): int {}
}
