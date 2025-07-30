<?php

declare(strict_types=1);

namespace Boson\Component\Http\Body\Tests;

use Boson\Contracts\Http\Component\Body\BodyProviderInterface;
use Boson\Contracts\Http\Component\Body\MutableBodyProviderInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/http-body-decoder')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testBodyProviderInterfaceCompatibility(): void
    {
        new class implements BodyProviderInterface {
            public string $body {
                get {}
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableBodyProviderInterfaceCompatibility(): void
    {
        new class implements MutableBodyProviderInterface {
            public string $body {
                get {}
                set(string|\Stringable $body) {}
            }
        };
    }
}
