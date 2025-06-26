<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family\Factory;

use Boson\Component\OsInfo\Family;
use Boson\Component\OsInfo\FamilyInterface;

/**
 * Factory that attempts to detect the OS family from environment variables.
 */
final readonly class EnvFamilyFactory implements OptionalFamilyFactoryInterface
{
    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_NAME = 'BOSON_OS_FAMILY';

    public function __construct(
        /**
         * @var list<non-empty-string>
         */
        private array $envVariableNames = [],
    ) {}

    /**
     * Creates an instance configured to use the default override
     * environment variable.
     */
    public static function createForOverrideEnvVariables(): self
    {
        return new self([
            self::DEFAULT_OVERRIDE_ENV_NAME,
        ]);
    }

    /**
     * @return non-empty-string|null
     */
    private function tryGetNameFromEnvironment(): ?string
    {
        foreach ($this->envVariableNames as $name) {
            $server = $_SERVER[$name] ?? null;

            if (\is_string($server) && $server !== '') {
                return $server;
            }
        }

        return null;
    }

    public function createFamily(): ?FamilyInterface
    {
        $name = $this->tryGetNameFromEnvironment();

        if ($name === null) {
            return null;
        }

        return Family::tryFrom($name);
    }
}
