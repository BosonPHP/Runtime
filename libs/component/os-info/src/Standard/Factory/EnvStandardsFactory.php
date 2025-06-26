<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Standard\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Standard;
use Boson\Component\OsInfo\StandardInterface;

/**
 * Factory that attempts to detect standards from environment variables.
 */
final readonly class EnvStandardsFactory implements StandardsFactoryInterface
{
    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_NAME = 'BOSON_OS_STANDARDS';

    public function __construct(
        /**
         * Default standards factory delegate to
         */
        private StandardsFactoryInterface $delegate,
        /**
         * @var list<non-empty-string>
         */
        private array $envVariableNames = [],
    ) {}

    /**
     * Creates an instance configured to use the default override
     * environment variable.
     */
    public static function createForOverrideEnvVariables(StandardsFactoryInterface $delegate): self
    {
        return new self($delegate, [
            self::DEFAULT_OVERRIDE_ENV_NAME,
        ]);
    }

    /**
     * @return non-empty-string|null
     */
    private function tryGetValueFromEnvironment(): ?string
    {
        foreach ($this->envVariableNames as $name) {
            $server = $_SERVER[$name] ?? null;

            if (\is_string($server) && $server !== '') {
                return $server;
            }
        }

        return null;
    }

    /**
     * @return non-empty-list<non-empty-string>|null
     */
    private function tryGetStandardsFromEnvironment(): ?array
    {
        $standardsStringValue = $this->tryGetValueFromEnvironment();

        if ($standardsStringValue === null) {
            return null;
        }

        $standardStringValues = [];

        // The ";" is a Windows separator
        foreach (\explode(';', $standardsStringValue) as $segment) {
            // The ":" is a *nix (macOS/Linux) separator
            foreach (\explode(':', $segment) as $standardStringValue) {
                $standardStringValue = \trim($standardStringValue);

                if ($standardStringValue !== '') {
                    $standardStringValues[] = $standardStringValue;
                }
            }
        }

        if ($standardStringValues === []) {
            return null;
        }

        return $standardStringValues;
    }

    /**
     * @return non-empty-list<StandardInterface>
     */
    public function createStandards(FamilyInterface $family): array
    {
        $standardStringValues = $this->tryGetStandardsFromEnvironment();

        if ($standardStringValues === null) {
            return $this->delegate->createStandards($family);
        }

        $standardInstances = [];

        foreach ($standardStringValues as $standardStringValue) {
            $standardInstance = Standard::tryFrom($standardStringValue);

            if ($standardInstance instanceof StandardInterface) {
                $standardInstances[] = $standardInstance;
            }
        }

        if ($standardInstances === []) {
            return $this->delegate->createStandards($family);
        }

        return $standardInstances;
    }
}
