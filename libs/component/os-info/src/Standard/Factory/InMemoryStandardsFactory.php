<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Standard\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\StandardInterface;

/**
 * Factory that caches (memoizes) the created standards list in memory for reuse.
 */
final class InMemoryStandardsFactory implements StandardsFactoryInterface
{
    /**
     * @var list<StandardInterface>|null
     */
    private ?array $standards = null;

    public function __construct(
        /**
         * Factory to delegate creation to
         */
        private readonly StandardsFactoryInterface $delegate,
    ) {}

    /**
     * @return list<StandardInterface>
     */
    public function createStandards(FamilyInterface $family): array
    {
        return $this->standards ??= $this->createNonMemoizedStandards($family);
    }

    /**
     * @return list<StandardInterface>
     */
    private function createNonMemoizedStandards(FamilyInterface $family): array
    {
        $standards = $this->delegate->createStandards($family);

        return \iterator_to_array($standards, false);
    }
}
