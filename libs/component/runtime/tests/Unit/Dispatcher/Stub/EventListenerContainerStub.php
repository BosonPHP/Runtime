<?php

declare(strict_types=1);

namespace Boson\Tests\Unit\Dispatcher\Stub;

use Boson\Contracts\EventListener\EventListenerInterface;
use Boson\Contracts\EventListener\Subscription\CancellableSubscriptionInterface;
use Boson\Contracts\EventListener\Subscription\SubscriptionInterface;
use Boson\Dispatcher\EventListener;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class EventListenerContainerStub implements
    EventListenerInterface,
    EventDispatcherInterface
{
    public function __construct(
        private EventListener $listener,
    ) {}

    public function dispatch(object $event): object
    {
        return $this->listener->dispatch($event);
    }

    public function addEventListener(string $event, callable $listener): CancellableSubscriptionInterface
    {
        return $this->listener->addEventListener($event, $listener);
    }

    public function removeEventListener(SubscriptionInterface $subscription): void
    {
        $this->listener->removeEventListener($subscription);
    }

    public function removeListenersForEvent(string|object $event): void
    {
        $this->listener->removeListenersForEvent($event);
    }

    public function getListenersForEvent(string|object $event): iterable
    {
        return $this->listener->getListenersForEvent($event);
    }
}
