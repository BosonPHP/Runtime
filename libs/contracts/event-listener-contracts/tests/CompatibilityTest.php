<?php

declare(strict_types=1);

namespace Boson\Contracts\EventListener\Tests;

use Boson\Contracts\EventListener\EventListenerInterface;
use Boson\Contracts\EventListener\Subscription\CancellableSubscriptionInterface;
use Boson\Contracts\EventListener\Subscription\SubscriptionInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/event-listener-contracts')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testEventListenerCompatibility(): void
    {
        new class implements EventListenerInterface {
            public function addEventListener(string $event, callable $listener): CancellableSubscriptionInterface {}

            public function removeEventListener(SubscriptionInterface $subscription): void {}

            public function removeListenersForEvent(object|string $event): void {}

            public function getListenersForEvent(object|string $event): iterable {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testSubscriptionCompatibility(): void
    {
        new class implements SubscriptionInterface {
            public int|string $id {
                get => '';
            }

            public string $name {
                get => '';
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testCancellableSubscriptionCompatibility(): void
    {
        new class implements CancellableSubscriptionInterface {
            public int|string $id {
                get => '';
            }

            public string $name {
                get => '';
            }

            public bool $isCancelled {
                get => false;
            }

            public function cancel(): void {}
        };
    }
}
