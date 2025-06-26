<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family\Factory;

use Boson\Component\OsInfo\FamilyInterface;

/**
 * Interface for factories that may optionally create a {@see FamilyInterface}
 * instance.
 */
interface OptionalFamilyFactoryInterface
{
    /**
     * Creates a {@see FamilyInterface} instance or returns {@see null}
     * if not available.
     */
    public function createFamily(): ?FamilyInterface;
}
