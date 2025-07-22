<?php

declare(strict_types=1);

namespace Boson\Bridge\Http\Tests;

use Boson\Bridge\Http\RequestAdapterInterface;
use Boson\Bridge\Http\ResponseAdapterInterface;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Http\ResponseInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/http-bridge')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testRequestAdapterCompatibility(): void
    {
        new class implements RequestAdapterInterface {
            public function createRequest(RequestInterface $request): object {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testResponseAdapterCompatibility(): void
    {
        new class implements ResponseAdapterInterface {
            public function createResponse(object $response): ResponseInterface {}
        };
    }
}
