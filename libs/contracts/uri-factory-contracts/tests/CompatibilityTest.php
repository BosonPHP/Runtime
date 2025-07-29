<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory\Tests;

use Boson\Contracts\Uri\Component\PathInterface;
use Boson\Contracts\Uri\Component\QueryInterface;
use Boson\Contracts\Uri\Component\SchemeInterface;
use Boson\Contracts\Uri\Factory\Component\UriPathFactoryInterface;
use Boson\Contracts\Uri\Factory\Component\UriQueryFactoryInterface;
use Boson\Contracts\Uri\Factory\Component\UriSchemeFactoryInterface;
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
            public function createUriFromString(string|\Stringable $uri): UriInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testSchemeFactoryInterfaceCompatibility(): void
    {
        new class implements UriSchemeFactoryInterface {
            public function createSchemeFromString(string|\Stringable $scheme): SchemeInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testQueryFactoryInterfaceCompatibility(): void
    {
        new class implements UriQueryFactoryInterface {
            public function createQueryFromString(string|\Stringable $query): QueryInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testPathFactoryInterfaceCompatibility(): void
    {
        new class implements UriPathFactoryInterface {
            public function createPathFromString(string|\Stringable $path): PathInterface {}
        };
    }
}
