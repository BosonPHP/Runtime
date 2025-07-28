<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory\Tests;

use Boson\Contracts\Uri\Component\PathInterface;
use Boson\Contracts\Uri\Component\QueryInterface;
use Boson\Contracts\Uri\Component\SchemeInterface;
use Boson\Contracts\Uri\Factory\PathFactoryInterface;
use Boson\Contracts\Uri\Factory\QueryFactoryInterface;
use Boson\Contracts\Uri\Factory\SchemeFactoryInterface;
use Boson\Contracts\Uri\Factory\UriFactoryInterface;
use Boson\Contracts\Uri\UriInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/uri-factory-contracts')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testUriFactoryInterfaceCompatibility(): void
    {
        new class implements UriFactoryInterface {
            public function createUriFromString(string $uri): UriInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testSchemeFactoryInterfaceCompatibility(): void
    {
        new class implements SchemeFactoryInterface {
            public function createSchemeFromString(string $scheme): SchemeInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testQueryFactoryInterfaceCompatibility(): void
    {
        new class implements QueryFactoryInterface {
            public function createQueryFromString(string $query): QueryInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testPathFactoryInterfaceCompatibility(): void
    {
        new class implements PathFactoryInterface {
            public function createPathFromString(string $path): PathInterface {}
        };
    }
}
