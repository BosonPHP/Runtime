<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver\Nix;

use FFI\Env\Runtime;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal Boson\Component\Pasm\Driver
 *
 * @mixin \FFI
 */
final readonly class Libc
{
    private \FFI $ffi;

    public function __construct(
        /**
         * @var non-empty-string
         */
        private string $library,
    ) {
        Runtime::assertAvailable();

        $this->ffi = $this->loadLibrary();
    }

    private function loadLibrary(): \FFI
    {
        $code = (string) @\file_get_contents(
            filename: __FILE__,
            offset: __COMPILER_HALT_OFFSET__,
        );

        return \FFI::cdef($code, $this->library);
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

void* mmap(void* addr, size_t length, int prot, int flags, int fd, off_t offset);

int mprotect(void* addr, size_t len, int prot);

int munmap(void* addr, size_t length);
