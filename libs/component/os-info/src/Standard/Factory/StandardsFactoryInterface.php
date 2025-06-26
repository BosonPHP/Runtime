<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Standard\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\StandardInterface;

/**
 * Interface for factories that are guaranteed to create a list
 * of standards for a given OS family.
 */
interface StandardsFactoryInterface extends OptionalStandardsFactoryInterface
{
    /**
     * @return iterable<array-key, StandardInterface>
     */
    public function createStandards(FamilyInterface $family): iterable;
}
