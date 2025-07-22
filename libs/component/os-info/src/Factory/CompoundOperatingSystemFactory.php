<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Factory;

use Boson\Component\OsInfo\OperatingSystem;

final readonly class CompoundOperatingSystemFactory implements OperatingSystemFactoryInterface
{
    /**
     * @var list<OptionalOperatingSystemFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<mixed, OptionalOperatingSystemFactoryInterface> $factories
     *        Factories to try in order
     */
    public function __construct(
        /**
         * Default factory to use if none succeed
         */
        private OperatingSystemFactoryInterface $default,
        iterable $factories = [],
    ) {
        $this->factories = \iterator_to_array($factories, false);
    }

    public function createOperatingSystem(): OperatingSystem
    {
        foreach ($this->factories as $factory) {
            $instance = $factory->createOperatingSystem();

            if ($instance instanceof OperatingSystem) {
                return $instance;
            }
        }

        return $this->default->createOperatingSystem();
    }
}
