<?php

declare(strict_types=1);

namespace Boson\Component\Http\Static\Tests;

use Boson\Component\Http\Static\StaticProviderInterface;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Http\ResponseInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/http-static-provider')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testStaticProviderInterfaceCompatibility(): void
    {
        new class implements StaticProviderInterface {
            public function findFileByRequest(RequestInterface $request): ?ResponseInterface {}
        };
    }
}
