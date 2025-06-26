<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Standard\Factory;

use Boson\Component\OsInfo\FamilyInterface;

/**
 * Factory that tries multiple {@see OptionalStandardsFactoryInterface}
 * implementations in order, returning the first successful standards list, or
 * a default if none succeed.
 */
final readonly class CompoundStandardsFactory implements StandardsFactoryInterface
{
    /**
     * @var list<OptionalStandardsFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<mixed, OptionalStandardsFactoryInterface> $factories
     *        Factories to try in order
     */
    public function __construct(
        /**
         * Default factory to use if none succeed
         */
        private StandardsFactoryInterface $default,
        iterable $factories = [],
    ) {
        $this->factories = \iterator_to_array($factories, false);
    }

    public function createStandards(FamilyInterface $family): iterable
    {
        foreach ($this->factories as $factory) {
            $instance = $factory->createStandards($family);

            if ($instance !== null) {
                return $instance;
            }
        }

        return $this->default->createStandards($family);
    }
}
