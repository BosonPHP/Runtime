<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Config;

use Boson\ApplicationCreateInfo;
use Spiral\Core\InjectableConfig;

final class BosonConfig extends InjectableConfig
{
    /**
     * @var non-empty-string
     */
    public const CONFIG = 'boson';

    /**
     * Default configuration for Boson.
     *
     * @var array{
     *     init-url: non-empty-string,
     *     static: list<non-empty-string>|non-empty-string,
     *     application: ApplicationCreateInfo|null,
     *     ...
     * }
     *
     * @phpstan-ignore-next-line : Invalid 3rd party phpdoc override
     */
    protected array $config = [
        /**
         * List of directories to serve static files from.
         */
        'static' => [],

        /**
         * The URL to initialize the application.
         */
        'init-url' => 'http://localhost/',

        /**
         * Application create configuration.
         *
         * @see getApplicationCreateInfo
         */
        'application' => null,
    ];

    /**
     * Path to the static files directory.
     *
     * @return list<non-empty-string>|non-empty-string
     */
    public function getStaticFiles(): array|string
    {
        return $this->config['static'] ?? [];
    }

    /**
     * The URL to initialize the application.
     *
     * @return non-empty-string
     */
    public function getInitUrl(): string
    {
        return $this->config['init-url'];
    }

    public function getApplicationCreateInfo(): ApplicationCreateInfo
    {
        return $this->config['application'] ?? new ApplicationCreateInfo();
    }
}
