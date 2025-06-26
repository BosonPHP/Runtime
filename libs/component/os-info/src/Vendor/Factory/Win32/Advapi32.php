<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory\Win32;

use FFI\Env\Runtime;

/**
 * @mixin \FFI
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Component\OsInfo\Vendor\Factory
 */
final readonly class Advapi32
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

        return \FFI::cdef($code, 'advapi32.dll');
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

typedef unsigned short wchar_t;
typedef intptr_t LONG_PTR;
typedef wchar_t WCHAR;
typedef char CHAR;
typedef long LONG;
typedef unsigned long DWORD;
typedef void *PVOID;
typedef const CHAR *LPCSTR;
typedef const WCHAR *LPCWSTR;
typedef PVOID HANDLE;
typedef HANDLE HKEY;
typedef LONG LSTATUS;
typedef DWORD *LPDWORD;

LSTATUS RegGetValueA(
    HKEY    hKey,
    LPCSTR  lpSubKey,
    LPCSTR  lpValue,
    DWORD   dwFlags,
    LPDWORD pdwType,
    PVOID   pvData,
    LPDWORD pcbData
);
