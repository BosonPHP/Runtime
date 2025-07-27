<?php

declare(strict_types=1);

namespace Boson\Component\GlobalsProvider\Tests;

use Boson\Component\GlobalsProvider\ServerGlobalsProviderInterface;
use Boson\Contracts\Http\RequestInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/globals-provider')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testServerGlobalsProviderInterfaceCompatibility(): void
    {
        new class implements ServerGlobalsProviderInterface {
            public function getServerGlobals(RequestInterface $request): array {}
        };
    }
} 