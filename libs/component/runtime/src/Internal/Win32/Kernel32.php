<?php

declare(strict_types=1);

namespace Boson\Internal\Win32;

use FFI\Env\Runtime;

/**
 * @mixin \FFI
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson
 */
final readonly class Kernel32
{
    private \FFI $ffi;

    public function __construct()
    {
        Runtime::assertAvailable();

        $this->ffi = \FFI::cdef((string) @\file_get_contents(
            filename: __FILE__,
            offset: __COMPILER_HALT_OFFSET__,
        ), 'kernel32.dll');
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

bool FreeConsole(void);
