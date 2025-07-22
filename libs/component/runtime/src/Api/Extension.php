<?php

declare(strict_types=1);

namespace Boson\Api;

use Boson\Contracts\EventListener\Subscription\CancellableSubscriptionInterface;
use Boson\Dispatcher\Event;
use Boson\Dispatcher\EventListener;
use Boson\Dispatcher\Intention;
use Boson\Internal\Saucer\LibSaucer;
use Boson\WebView\WebView;
use FFI\CData;

/**
 * @template T of object
 */
abstract class Extension
{
    protected readonly CData $ptr;

    public function __construct(
        protected readonly LibSaucer $api,
        /**
         * @var T
         */
        protected readonly object $context,
        private readonly EventListener $listener,
    ) {
        $this->ptr = $this->getHandle($this->context);
    }

    /**
     * @param T $context
     */
    abstract protected function getHandle(object $context): CData;

    /**
     * @template TArgEvent of object
     *
     * @param class-string<TArgEvent> $event the event (class) name
     * @param callable(TArgEvent):void $then the listener callback
     *
     * @return CancellableSubscriptionInterface<TArgEvent>
     */
    protected function listen(string $event, callable $then): CancellableSubscriptionInterface
    {
        return $this->listener->addEventListener($event, $then);
    }

    /**
     * @param Intention<WebView> $intention
     */
    protected function intent(object $intention): bool
    {
        $this->listener->dispatch($intention);

        return $intention->isCancelled === false;
    }

    /**
     * @param Event<WebView> $event
     */
    protected function dispatch(object $event): void
    {
        $this->listener->dispatch($event);
    }
}
