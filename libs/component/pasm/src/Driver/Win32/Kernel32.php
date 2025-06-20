<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver\Win32;

use FFI\Env\Runtime;

/**
 * @mixin \FFI
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Component\Pasm\Driver
 */
final readonly class Kernel32
{
    private \FFI $ffi;

    public function __construct()
    {
        Runtime::assertAvailable();

        $this->ffi = $this->loadLibrary();
    }

    private function loadLibrary(): \FFI
    {
        $code = (string) @\file_get_contents(
            filename: __FILE__,
            offset: __COMPILER_HALT_OFFSET__,
        );

        return \FFI::cdef($code, 'kernel32.dll');
    }

    /**
     * @param non-empty-string $name
     * @param array<array-key, mixed> $arguments
     */
    public function __call(string $name, array $arguments = []): mixed
    {
        try {
            return $this->ffi->$name(...$arguments);
        } catch (\Throwable $e) {
            throw new \BadMethodCallException($e->getMessage(), previous: $e);
        }
    }
}

__halt_compiler();

typedef void *LPVOID;
typedef size_t SIZE_T;  // typedef ULONG_PTR SIZE_T;
typedef unsigned long DWORD;
typedef DWORD *PDWORD;
typedef bool BOOL;      // typedef int BOOL;

LPVOID VirtualAlloc(
    LPVOID lpAddress,
    SIZE_T dwSize,
    DWORD flAllocationType,
    DWORD flProtect
);

BOOL VirtualProtect(
    LPVOID lpAddress,
    SIZE_T dwSize,
    DWORD flNewProtect,
    PDWORD lpflOldProtect
);

BOOL VirtualFree(
    LPVOID lpAddress,
    SIZE_T dwSize,
    DWORD dwFreeType
);

DWORD GetLastError();
