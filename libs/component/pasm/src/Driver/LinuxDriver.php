<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver;

use Boson\Component\Pasm\Driver\Nix\Libc;
use Boson\Component\Pasm\Exception\InternalErrorException;
use Boson\Component\WeakType\ObservableWeakMap;
use FFI\CData;

/**
 * Abstract *nix driver for executing and compiling assembly (machine) code.
 */
class LinuxDriver implements DriverInterface
{
    /**
     * Memory protection flag: readable.
     */
    final protected const int PROT_READ = 0x01;

    /**
     * Memory protection flag: writable.
     */
    final protected const int PROT_WRITE = 0x02;

    /**
     * Memory protection flag: executable.
     */
    final protected const int PROT_EXEC = 0x04;

    /**
     * The {@see Libc::mmap()} failure return value.
     */
    final protected const int MAP_FAILED = -0x01;

    /**
     * The {@see Libc::mmap()} flag: private mapping.
     */
    final protected const int MAP_PRIVATE = 0x02;

    /**
     * The {@see Libc::mmap()} flag: anonymous mapping.
     */
    final protected const int MAP_ANONYMOUS = 0x20;

    /**
     * @var int<-2147483648, 2147483647>
     */
    protected const int ALLOC_PROTECT = self::PROT_READ | self::PROT_WRITE;

    /**
     * @var int<-2147483648, 2147483647>
     */
    protected const int ALLOC_FLAGS = self::MAP_PRIVATE | self::MAP_ANONYMOUS;

    /**
     * @var int<-2147483648, 2147483647>
     */
    protected const int EXEC_PROTECT = self::PROT_READ | self::PROT_EXEC;

    private readonly Libc $libc;

    public bool $isSupported {
        get => \PHP_OS_FAMILY === 'Linux';
    }

    /**
     * Stores closures and their associated memory for automatic cleanup.
     *
     * @var ObservableWeakMap<CData&callable(mixed...):mixed>
     */
    private readonly ObservableWeakMap $programs;

    public function __construct()
    {
        $this->programs = new ObservableWeakMap();

        $this->libc = new \ReflectionClass(Libc::class)
            ->newLazyProxy(fn() => $this->createLibc());
    }

    protected function createLibc(): Libc
    {
        return new Libc('libc.so.6');
    }

    public function compile(string $signature, string $code): callable
    {
        $length = \strlen($code);

        // Allocate the memory
        $memory = $this->allocate($length);

        // Copy code to the memory
        \FFI::memcpy($memory, $code, $length);

        // Make executable
        $this->protect($memory, $length);

        /**
         * Cast to closure-like
         *
         * @var CData&callable(mixed...):mixed $closure
         */
        $closure = $this->libc->cast($signature, $memory);

        /**
         * For correct memory release, the allocation size
         * (`$length` variable) should be tracked.
         *
         * @phpstan-ignore-next-line PHPStan false-positive, 3rd argument should be callable(CData):void
         */
        return $this->programs->watch(
            $closure,
            $memory,
            fn(CData $mem): null
            => $this->onRelease($mem, $length),
        );
    }

    /**
     * Allocates memory for code execution using the {@see Libc::mmap()}.
     *
     * @param int<0, max> $length number of bytes to allocate
     *
     * @throws InternalErrorException in case of allocation fails
     */
    private function allocate(int $length): CData
    {
        /** @var int<-2147483648, 2147483647> $prot */
        $prot = static::ALLOC_PROTECT;

        /** @var int<-2147483648, 2147483647> $flags */
        $flags = static::ALLOC_FLAGS;

        $memory = $this->libc->mmap(null, $length, $prot, $flags, -1, 0);

        $isNullPointer = $memory === null || \FFI::isNull($memory);

        /**
         * On error, the value `MAP_FAILED` (that is, `(void *) -1`) is
         * returned, and `errno` is set to indicate the error.
         *
         * @link https://man7.org/linux/man-pages/man2/mmap.2.html
         */
        if ($isNullPointer || $this->libc->cast('intptr_t', $memory) === self::MAP_FAILED) {
            throw InternalErrorException::becauseInternalErrorOccurs(
                message: 'mmap failed (memory allocation error)',
            );
        }

        return $memory;
    }

    /**
     * Changes the protection on a region of memory to executable.
     *
     * @param int<0, max> $length number of bytes in the region
     *
     * @throws InternalErrorException in case of protection fails
     */
    private function protect(CData $memory, int $length): void
    {
        $result = $this->libc->mprotect($memory, $length, static::EXEC_PROTECT);

        /**
         * On error, the {@see Libc::mprotect()} system calls return `-1`,
         * and `errno` is set to indicate the error.
         *
         * @link https://man7.org/linux/man-pages/man2/mprotect.2.html
         */
        if ($result === 0) {
            return;
        }

        throw InternalErrorException::becauseInternalErrorOccurs(
            message: 'mprotect failed (could not make memory executable)',
        );
    }

    /**
     * Releases a region of memory previously allocated for code execution.
     *
     * @param int<0, max> $length number of bytes to release
     *
     * @throws InternalErrorException in case of release fails
     */
    private function onRelease(CData $memory, int $length): void
    {
        $result = $this->libc->munmap($memory, $length);

        /**
         * Upon successful completion, {@see Libc::munmap()} shall return `0`;
         * otherwise, it shall return `-1` and set errno to indicate the error.
         *
         * @link https://man7.org/linux/man-pages/man3/munmap.3p.html
         */
        if ($result === 0) {
            return;
        }

        throw InternalErrorException::becauseInternalErrorOccurs(
            message: 'munmap failed',
        );
    }
}
