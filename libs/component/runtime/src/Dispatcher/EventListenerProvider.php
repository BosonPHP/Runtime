<?php

declare(strict_types=1);

namespace Boson\Dispatcher;

use Boson\Contracts\EventListener\EventListenerInterface;
use Boson\Contracts\EventListener\Subscription\CancellableSubscriptionInterface;
use Boson\Contracts\EventListener\Subscription\SubscriptionInterface;

/**
 * @phpstan-require-implements EventListenerInterface
 * @mixin EventListenerInterface
 */
trait EventListenerProvider
{
    //
    // PHP 8.4 does not support abstract properties in traits
    //
    // abstract protected EventListener $listener { get; }
    //

    /**
     * @template TArgEvent of object
     *
     * @param (\Closure(TArgEvent):void)|class-string<TArgEvent> $eventOrListener
     * @param (\Closure(TArgEvent):void)|null $listener
     *
     * @return CancellableSubscriptionInterface<TArgEvent>
     */
    public function on(\Closure|string $eventOrListener, ?\Closure $listener = null): CancellableSubscriptionInterface
    {
        if ($eventOrListener instanceof \Closure) {
            return $this->addEventListenerByCallback($eventOrListener);
        }

        if ($listener === null) {
            throw new \InvalidArgumentException('Second parameter must be a listener callback');
        }

        return $this->addEventListener($eventOrListener, $listener);
    }

    /**
     * @template TArgEvent of object
     *
     * @param \Closure(TArgEvent):void $listener
     *
     * @return CancellableSubscriptionInterface<TArgEvent>
     */
    private function addEventListenerByCallback(\Closure $listener): CancellableSubscriptionInterface
    {
        try {
            $parameters = new \ReflectionFunction($listener)
                ->getParameters();
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException('Could not parse event listener', previous: $e);
        }

        foreach ($parameters as $parameter) {
            /** @var class-string<TArgEvent> $type */
            $type = $this->getParameterTypeName($parameter);

            return $this->addEventListener($type, $listener);
        }

        throw new \InvalidArgumentException(
            message: 'The event subscriber must have at least one (event instance) parameter',
        );
    }

    /**
     * @return non-empty-string
     */
    private function getParameterTypeName(\ReflectionParameter $parameter): string
    {
        $type = $parameter->getType();

        if ($type instanceof \ReflectionNamedType) {
            /** @var non-empty-string */
            return $type->getName();
        }

        throw new \InvalidArgumentException(\sprintf(
            'Argument #%d ($%s) of event listener must contain listened event type-hint',
            $parameter->getPosition(),
            $parameter->getName(),
        ));
    }

    public function addEventListener(string $event, callable $listener): CancellableSubscriptionInterface
    {
        return $this->listener->addEventListener($event, $listener);
    }

    public function removeEventListener(SubscriptionInterface $subscription): void
    {
        $this->listener->removeEventListener($subscription);
    }

    public function removeListenersForEvent(object|string $event): void
    {
        $this->listener->removeListenersForEvent($event);
    }

    /**
     * @template TArgEvent of object
     *
     * @param class-string<TArgEvent>|TArgEvent $event
     *
     * @return array<array-key, callable(TArgEvent):void>
     */
    public function getListenersForEvent(object|string $event): array
    {
        return $this->listener->getListenersForEvent($event);
    }
}
