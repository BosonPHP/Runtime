<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family\Factory;

use Boson\Component\OsInfo\Family;
use Boson\Component\OsInfo\FamilyInterface;

/**
 * Factory that attempts to detect the OS family from environment variables.
 */
final readonly class EnvFamilyFactory implements FamilyFactoryInterface
{
    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_FAMILY = 'BOSON_OS_FAMILY';

    public function __construct(
        /**
         * Default family factory delegate to
         */
        private FamilyFactoryInterface $delegate,
        /**
         * @var list<non-empty-string>
         */
        private array $envVariableNames = [],
    ) {}

    /**
     * Creates an instance configured to use the default override
     * environment variable.
     */
    public static function createForOverrideEnvVariables(FamilyFactoryInterface $delegate): self
    {
        return new self($delegate, [
            self::DEFAULT_OVERRIDE_ENV_FAMILY,
        ]);
    }

    /**
     * @return non-empty-string|null
     */
    private function tryGetFamilyFromEnvironmentAsString(): ?string
    {
        foreach ($this->envVariableNames as $name) {
            $server = $_SERVER[$name] ?? null;

            if (\is_string($server) && $server !== '') {
                return $server;
            }
        }

        return null;
    }

    public function createFamily(): FamilyInterface
    {
        $name = $this->tryGetFamilyFromEnvironmentAsString();

        if ($name === null) {
            return $this->delegate->createFamily();
        }

        return Family::tryFrom($name)
            ?? $this->delegate->createFamily();
    }
}
