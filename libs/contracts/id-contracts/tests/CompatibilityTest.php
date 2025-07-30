<?php

declare(strict_types=1);

namespace Boson\Contracts\Id\Tests;

use Boson\Contracts\Id\IdentifiableInterface;
use Boson\Contracts\Id\IdInterface;
use Boson\Contracts\Id\IntIdInterface;
use Boson\Contracts\Id\StringIdInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/id-contracts')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testIdInterfaceCompatibility(): void
    {
        new class implements IdInterface {
            public function equals(mixed $other): bool {}

            public function __toString(): string {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testStringIdInterfaceCompatibility(): void
    {
        new class implements StringIdInterface {
            public function equals(mixed $other): bool {}

            public function __toString(): string {}

            public function toString(): string {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testIntIdInterfaceCompatibility(): void
    {
        new class implements IntIdInterface {
            public function equals(mixed $other): bool {}

            public function __toString(): string {}

            public function toInteger(): int {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testIdentifiableInterfaceCompatibility(): void
    {
        new class implements IdentifiableInterface {
            public IdInterface $id {
                get {}
            }
        };
    }
}
