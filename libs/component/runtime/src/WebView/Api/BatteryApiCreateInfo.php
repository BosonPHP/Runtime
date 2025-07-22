<?php

declare(strict_types=1);

namespace Boson\WebView\Api;

/**
 * Configuration class for Battery API initialization.
 */
final readonly class BatteryApiCreateInfo
{
    /**
     * Default value for enabling battery events.
     *
     * When set to {@see false}, battery-related events
     * will not be dispatched.
     *
     * Note: All battery-related events are disabled by default to
     *       optimize and speed up the application.
     */
    public const bool DEFAULT_ENABLE_EVENTS = false;

    public function __construct(
        /**
         * Whether to enable battery-related events.
         *
         * When {@see true}, events like battery level changes,
         * charging state changes, etc. will be dispatched.
         */
        public bool $enableEvents = self::DEFAULT_ENABLE_EVENTS,
    ) {}
}
