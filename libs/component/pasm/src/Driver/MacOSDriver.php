<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver;

use Boson\Component\Pasm\Driver\Nix\Libc;

/**
 * MacOS driver for executing and compiling assembly (machine) code.
 */
class MacOSDriver extends LinuxDriver
{
    public bool $isSupported {
        get => \PHP_OS_FAMILY === 'Darwin';
    }

    /**
     * The {@see self::MAP_ANONYMOUS} flag on MacOS is `0x1000`, not `0x20`.
     *
     * @link https://developer.apple.com/library/archive/documentation/System/Conceptual/ManPages_iPhoneOS/man2/mmap.2.html
     * @link https://github.com/nneonneo/osx-10.9-opensource/blob/master/xnu-2422.1.72/bsd/sys/mman.h#L150
     * @link https://stackoverflow.com/questions/44615134/what-is-wrong-with-mmap-system-call-on-mac-os-x
     */
    private const int MAP_ANON = 0x1000;

    /**
     * Overrides default alloc ({@see Libc::mmap()}) flags.
     *
     * @var int<-2147483648, 2147483647>
     */
    protected const int ALLOC_FLAGS = self::MAP_PRIVATE | self::MAP_ANON;

    #[\Override]
    protected function createLibc(): Libc
    {
        return new Libc('libc.dylib');
    }
}
