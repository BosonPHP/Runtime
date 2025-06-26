<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family\Factory;

use Boson\Component\OsInfo\FamilyInterface;

/**
 * Factory that tries multiple {@see OptionalFamilyFactoryInterface}
 * implementations in order, returning the first successful
 * {@see FamilyInterface} instance, or a default if none succeed.
 */
final readonly class CompoundFamilyFactory implements FamilyFactoryInterface
{
    /**
     * @var list<OptionalFamilyFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<mixed, OptionalFamilyFactoryInterface> $factories
     *        Factories to try in order
     */
    public function __construct(
        /**
         * Default factory to use if none succeed.
         */
        private FamilyFactoryInterface $default,
        iterable $factories = [],
    ) {
        $this->factories = \iterator_to_array($factories, false);
    }

    public function createFamily(): FamilyInterface
    {
        foreach ($this->factories as $factory) {
            $instance = $factory->createFamily();

            if ($instance instanceof FamilyInterface) {
                return $instance;
            }
        }

        return $this->default->createFamily();
    }
}
