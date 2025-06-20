<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Driver;

use Boson\Component\Pasm\ExecutorInterface;

/**
 * Interface for drivers capable of executing and compiling assembly code.
 */
interface DriverInterface extends ExecutorInterface
{
    /**
     * Indicates whether the driver is supported
     * in the current environment.
     */
    public bool $isSupported {
        /**
         * Returns {@see true} if the driver can be
         * used, {@see false} otherwise.
         */
        get;
    }
}
