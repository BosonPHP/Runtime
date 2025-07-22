<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Standard\Factory;

use Boson\Component\OsInfo\FamilyInterface;

/**
 * Default implementation of {@see StandardsFactoryInterface}.
 *
 * Uses a chain of factories to determine the standards for a given OS family.
 */
final readonly class DefaultStandardsFactory implements StandardsFactoryInterface
{
    private StandardsFactoryInterface $default;

    public function __construct()
    {
        $this->default = EnvStandardsFactory::createForOverrideEnvVariables(
            delegate: new GenericStandardsFactory(),
        );
    }

    public function createStandards(FamilyInterface $family): iterable
    {
        return $this->default->createStandards($family);
    }
}
