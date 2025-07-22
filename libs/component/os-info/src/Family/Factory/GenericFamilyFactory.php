<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family\Factory;

use Boson\Component\OsInfo\Family;
use Boson\Component\OsInfo\FamilyInterface;

/**
 * Factory that creates a {@see FamilyInterface} based on
 * a provided or default OS family name.
 */
final readonly class GenericFamilyFactory implements FamilyFactoryInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        private string $name = \PHP_OS_FAMILY,
    ) {}

    public function createFamily(): FamilyInterface
    {
        return Family::tryFrom($this->name)
            ?? new Family($this->name);
    }
}
