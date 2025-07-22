<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver;

use Boson\Component\Pasm\Driver\Win32\Kernel32;
use Boson\Component\Pasm\Exception\InternalErrorException;
use Boson\Component\WeakType\ObservableWeakMap;
use FFI\CData;

/**
 * Windows driver for executing and compiling assembly (machine) code.
 */
class Win32Driver implements DriverInterface
{
    /**
     * Enables read-only or read/write access to the committed region
     * of pages. If Data Execution Prevention is enabled, attempting
     * to execute code in the committed region
     * results in an access violation.
     */
    private const int PAGE_READWRITE = 0x0000_0004;

    /**
     * Enables execute or read-only access to the committed region
     * of pages. An attempt to write to the committed region
     * results in an access violation.
     */
    private const int PAGE_EXECUTE_READ = 0x0000_0020;

    /**
     * Allocates memory charges (from the overall size of memory and the
     * paging files on disk) for the specified reserved memory pages.
     * The function also guarantees that when the caller later initially
     * accesses the memory, the contents will be zero. Actual physical
     * pages are not allocated unless/until the virtual addresses are
     * actually accessed.
     */
    private const int MEM_COMMIT = 0x0000_1000;

    /**
     * Reserves a range of the process's virtual address space without
     * allocating any actual physical storage in memory or in the
     * paging file on disk.
     */
    private const int MEM_RESERVE = 0x0000_2000;

    /**
     * Releases the specified region of pages, or placeholder (for a
     * placeholder, the address space is released and available for
     * other allocations). After this operation, the pages
     * are in the free state.
     */
    private const int MEM_RELEASE = 0x0000_8000;

    /**
     * 0x3000 = MEM_COMMIT | MEM_RESERVE
     */
    private const int ALLOC_TYPE = self::MEM_COMMIT | self::MEM_RESERVE;

    /**
     * 0x0004 = PAGE_READWRITE
     */
    private const int ALLOC_PROTECT = self::PAGE_READWRITE;

    /**
     * 0x0020 = PAGE_EXECUTE_READ
     */
    private const int EXEC_PROTECT = self::PAGE_EXECUTE_READ;

    /**
     * 0x8000 = MEM_RELEASE
     */
    private const int FREE_TYPE = self::MEM_RELEASE;

    private readonly Kernel32 $kernel32;

    /**
     * Stores closures and their associated memory for automatic cleanup.
     *
     * @var ObservableWeakMap<CData&callable(mixed...):mixed>
     */
    private readonly ObservableWeakMap $programs;

    public bool $isSupported {
        get => \PHP_OS_FAMILY === 'Windows';
    }

    public function __construct()
    {
        $this->programs = new ObservableWeakMap();

        $this->kernel32 = new \ReflectionClass(Kernel32::class)
            ->newLazyProxy(static fn() => new Kernel32());
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
        $closure = $this->kernel32->cast($signature, $memory);

        /** @phpstan-ignore-next-line : PHPStan false-positive, 3rd argument should be callable(CData):void */
        return $this->programs->watch($closure, $memory, $this->onRelease(...));
    }

    /**
     * Reserves of a region of pages in the virtual address space
     * of the calling process.
     *
     * @param int<0, max> $length
     *
     * @throws InternalErrorException
     */
    private function allocate(int $length): CData
    {
        $memory = $this->kernel32->VirtualAlloc(
            null,
            $length,
            self::ALLOC_TYPE,
            self::ALLOC_PROTECT,
        );

        if ($memory !== null && !\FFI::isNull($memory)) {
            return $memory;
        }

        throw InternalErrorException::becauseInternalErrorOccurs(\sprintf(
            'VirtualAlloc failed (0x%x)',
            $this->kernel32->GetLastError(),
        ));
    }

    /**
     * Changes the protection on a region of memory to executable.
     *
     * @param int<0, max> $length number of bytes in the region
     *
     * @throws InternalErrorException
     */
    private function protect(CData $memory, int $length): void
    {
        $previous = $this->kernel32->new('DWORD');

        $isExecutable = $this->kernel32->VirtualProtect(
            $memory,
            $length,
            self::EXEC_PROTECT,
            \FFI::addr($previous),
        );

        if ($isExecutable !== false) {
            return;
        }

        throw InternalErrorException::becauseInternalErrorOccurs(\sprintf(
            'VirtualProtect failed (0x%x)',
            $this->kernel32->GetLastError(),
        ));
    }

    /**
     * Releases a region of memory previously allocated for code execution.
     *
     * @throws InternalErrorException
     */
    private function onRelease(CData $memory): void
    {
        $isFreed = $this->kernel32->VirtualFree($memory, 0, self::FREE_TYPE);

        if ($isFreed !== false) {
            return;
        }

        throw InternalErrorException::becauseInternalErrorOccurs(\sprintf(
            'VirtualFree failed (0x%x)',
            $this->kernel32->GetLastError(),
        ));
    }
}
