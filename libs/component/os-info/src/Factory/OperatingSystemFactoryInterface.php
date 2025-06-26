<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Factory;

use Boson\Component\OsInfo\OperatingSystem;

interface OperatingSystemFactoryInterface extends OptionalOperatingSystemFactoryInterface
{
    public function createOperatingSystem(): OperatingSystem;
}
