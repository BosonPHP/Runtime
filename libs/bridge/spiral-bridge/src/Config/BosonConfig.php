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
     *     static: list<non-empty-string>|non-empty-string
     * }
     */
    protected array $config = [
        'static' => [],
    ];

    /**
     * Path to the static files directory.
     *
     * @return iterable<mixed, non-empty-string>|non-empty-string
     */
    public function getStaticFiles(): iterable|string
    {
        return $this->config['static'] ?? [];
    }
}
