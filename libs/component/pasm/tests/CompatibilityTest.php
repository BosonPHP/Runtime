<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Tests;

use Boson\Component\Pasm\ExecutorInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/pasm')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testExecutorInterfaceCompatibility(): void
    {
        new class implements ExecutorInterface {
            public function compile(string $signature, string $code): callable {}
        };
    }
} 