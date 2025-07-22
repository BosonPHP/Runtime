<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family\Factory;

use Boson\Component\OsInfo\FamilyInterface;

/**
 * Factory that caches (memoize) the created {@see FamilyInterface}
 * instance in memory for reuse.
 */
final class InMemoryFamilyFactory implements FamilyFactoryInterface
{
    private ?FamilyInterface $family = null;

    public function __construct(
        /**
         * Factory to delegate creation to
         */
        private readonly FamilyFactoryInterface $delegate,
    ) {}

    public function createFamily(): FamilyInterface
    {
        return $this->family ??= $this->delegate->createFamily();
    }
}
