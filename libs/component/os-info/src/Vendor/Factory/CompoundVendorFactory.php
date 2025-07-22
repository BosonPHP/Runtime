<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Vendor\VendorInfo;

final readonly class CompoundVendorFactory implements VendorFactoryInterface
{
    /**
     * @var list<OptionalVendorFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<mixed, OptionalVendorFactoryInterface> $factories
     *        Factories to try in order
     */
    public function __construct(
        /**
         * Default factory to use if none succeed
         */
        private VendorFactoryInterface $default,
        iterable $factories = [],
    ) {
        $this->factories = \iterator_to_array($factories, false);
    }

    public function createVendor(FamilyInterface $family): VendorInfo
    {
        foreach ($this->factories as $factory) {
            $instance = $factory->createVendor($family);

            if ($instance instanceof VendorInfo) {
                return $instance;
            }
        }

        return $this->default->createVendor($family);
    }
}
