<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Standard\Factory;

use Boson\Component\OsInfo\Family;
use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Standard;
use Boson\Component\OsInfo\StandardInterface;

/**
 * Factory that creates a default set of standards for a given OS family.
 */
final readonly class GenericStandardsFactory implements StandardsFactoryInterface
{
    /**
     * @return list<StandardInterface>
     */
    public function createStandards(FamilyInterface $family): array
    {
        if ($family->is(Family::Unix)) {
            return [Standard::Posix];
        }

        return [];
    }
}
