<?php

declare(strict_types=1);

namespace Boson\Contracts\ValueObject\Tests;

use Boson\Contracts\ValueObject\IntValueObjectInterface;
use Boson\Contracts\ValueObject\StringValueObjectInterface;
use Boson\Contracts\ValueObject\ValueObjectInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/value-object-contracts')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testValueObjectInterfaceCompatibility(): void
    {
        new class implements ValueObjectInterface {
            public function equals(mixed $other): bool {}

            public function __toString(): string {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testStringValueObjectInterfaceCompatibility(): void
    {
        new class implements StringValueObjectInterface {
            public function equals(mixed $other): bool {}

            public function __toString(): string {}

            public function toString(): string {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testIntValueObjectInterfaceCompatibility(): void
    {
        new class implements IntValueObjectInterface {
            public function equals(mixed $other): bool {}

            public function __toString(): string {}

            public function toInteger(): int {}
        };
    }
}
