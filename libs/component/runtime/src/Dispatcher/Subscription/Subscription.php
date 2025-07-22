<?php

declare(strict_types=1);

namespace Boson\Dispatcher\Subscription;

use Boson\Contracts\EventListener\Subscription\SubscriptionInterface;

/**
 * @template T of object
 *
 * @template-implements SubscriptionInterface<T>
 */
class Subscription implements SubscriptionInterface
{
    public function __construct(
        /**
         * @var array-key
         */
        public readonly int|string $id,
        /**
         * @var class-string<T>
         */
        public readonly string $name,
    ) {}
}
