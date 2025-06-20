<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver;

/**
 * BSD driver for executing and compiling assembly (machine) code.
 *
 * Note: Behaviour is similar to Linux
 */
class BSDDriver extends LinuxDriver
{
    public bool $isSupported {
        get => \PHP_OS_FAMILY === 'BSD';
    }
}
