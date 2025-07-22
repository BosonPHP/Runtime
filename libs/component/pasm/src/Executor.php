<?php

declare(strict_types=1);

namespace Boson\Component\Pasm;

use Boson\Component\Pasm\Driver\BSDDriver;
use Boson\Component\Pasm\Driver\DriverInterface;
use Boson\Component\Pasm\Driver\LinuxDriver;
use Boson\Component\Pasm\Driver\MacOSDriver;
use Boson\Component\Pasm\Driver\Win32Driver;
use Boson\Component\Pasm\Exception\NoAvailableDriverException;
use FFI\Env\Runtime;

/**
 * Executes assembly (machine) code using available drivers.
 */
final readonly class Executor implements ExecutorInterface
{
    /**
     * List of available drivers for code execution.
     *
     * @var list<DriverInterface>
     */
    private array $drivers;

    /**
     * @param iterable<mixed, DriverInterface>|null $drivers
     */
    public function __construct(?iterable $drivers = null)
    {
        $this->drivers = \iterator_to_array($drivers ?? $this->getDefaultDrivers(), false);
    }

    /**
     * @return non-empty-list<DriverInterface>
     */
    private function getDefaultDrivers(): array
    {
        return [
            new Win32Driver(),
            new LinuxDriver(),
            new MacOSDriver(),
            new BSDDriver(),
        ];
    }

    /**
     * Selects the first supported driver from the list.
     */
    private function select(): ?DriverInterface
    {
        foreach ($this->drivers as $driver) {
            if ($driver->isSupported) {
                return $driver;
            }
        }

        return null;
    }

    public function compile(string $signature, string $code): callable
    {
        Runtime::assertAvailable();

        $driver = $this->select();

        return $driver?->compile($signature, $code)
            ?? throw NoAvailableDriverException::becauseNoDriverSupported();
    }
}
