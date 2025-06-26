<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family\Factory;

use Boson\Component\OsInfo\FamilyInterface;

/**
 * Interface for factories that are guaranteed to create a
 * {@see FamilyInterface} instance.
 */
interface FamilyFactoryInterface extends OptionalFamilyFactoryInterface
{
    /**
     * Creates and returns a {@see FamilyInterface} instance.
     */
    public function createFamily(): FamilyInterface;
}
