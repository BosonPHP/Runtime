<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet\Factory;

use Boson\Component\CpuInfo\ArchitectureInterface;

final readonly class CompoundInstructionSetFactory implements InstructionSetFactoryInterface
{
    /**
     * @var list<OptionalInstructionSetFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<mixed, OptionalInstructionSetFactoryInterface> $factories
     *        Factories to try in order
     */
    public function __construct(
        /**
         * Default factory to use if none succeed
         */
        private InstructionSetFactoryInterface $default,
        iterable $factories = [],
    ) {
        $this->factories = \iterator_to_array($factories, false);
    }

    public function createInstructionSets(ArchitectureInterface $arch): iterable
    {
        foreach ($this->factories as $factory) {
            $instance = $factory->createInstructionSets($arch);

            if ($instance !== null) {
                return $instance;
            }
        }

        return $this->default->createInstructionSets($arch);
    }
}
