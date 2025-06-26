<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Standard\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\StandardInterface;

/**
 * Interface for factories that may optionally create a list
 * of standards for a given OS family.
 */
interface OptionalStandardsFactoryInterface
{
    /**
     * @return iterable<array-key, StandardInterface>|null
     */
    public function createStandards(FamilyInterface $family): ?iterable;
}
