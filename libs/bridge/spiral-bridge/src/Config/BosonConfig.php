<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Config;

use Spiral\Core\InjectableConfig;

final class BosonConfig extends InjectableConfig
{
    public const CONFIG = 'boson';

    /**
     * Default configuration for Boson.
     *
     * @var array{
     *     init-url: non-empty-string,
     *     schemes: list<non-empty-string>,
     *     static: list<non-empty-string>|non-empty-string
     * }
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
         * list of scheme names that the application will handle.
         */
        'schemes' => ['http'],
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

    /**
     * List of scheme names that the application will handle.
     *
     * @return list<non-empty-string>
     */
    public function getSchemes(): array
    {
        return $this->config['schemes'];
    }
}
