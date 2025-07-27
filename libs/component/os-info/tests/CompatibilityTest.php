<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Tests;

use Boson\Component\OsInfo\StandardInterface;
use Boson\Component\OsInfo\FamilyInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/os-info')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testStandardInterfaceCompatibility(): void
    {
        new class implements StandardInterface {
            public string $name {
                get {}
            }
            public ?StandardInterface $parent {
                get {}
            }
            public function isSupports(StandardInterface $standard): bool {}
            public function __toString(): string {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testFamilyInterfaceCompatibility(): void
    {
        new class implements FamilyInterface {
            public string $name {
                get {}
            }
            public ?FamilyInterface $parent {
                get {}
            }
            public function is(FamilyInterface $family): bool {}
            public function __toString(): string {}
        };
    }
} 