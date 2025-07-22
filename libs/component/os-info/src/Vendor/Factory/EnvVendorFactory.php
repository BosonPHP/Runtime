<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Vendor\VendorInfo;

/**
 * Factory that attempts to detect standards from environment variables.
 */
final readonly class EnvVendorFactory implements VendorFactoryInterface
{
    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_NAME = 'BOSON_OS_NAME';

    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_VERSION = 'BOSON_OS_VERSION';

    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_CODENAME = 'BOSON_OS_CODENAME';

    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_EDITION = 'BOSON_OS_EDITION';

    public function __construct(
        /**
         * Default standards factory delegate to
         */
        private VendorFactoryInterface $delegate,
        /**
         * @var list<non-empty-string>
         */
        private array $nameEnvVariableNames = [],
        /**
         * @var list<non-empty-string>
         */
        private array $versionEnvVariableNames = [],
        /**
         * @var list<non-empty-string>
         */
        private array $codenameEnvVariableNames = [],
        /**
         * @var list<non-empty-string>
         */
        private array $editionEnvVariableNames = [],
    ) {}

    /**
     * Creates an instance configured to use the default override
     * environment variable.
     */
    public static function createForOverrideEnvVariables(VendorFactoryInterface $delegate): self
    {
        return new self(
            delegate: $delegate,
            nameEnvVariableNames: [self::DEFAULT_OVERRIDE_ENV_NAME],
            versionEnvVariableNames: [self::DEFAULT_OVERRIDE_ENV_VERSION],
            codenameEnvVariableNames: [self::DEFAULT_OVERRIDE_ENV_CODENAME],
            editionEnvVariableNames: [self::DEFAULT_OVERRIDE_ENV_EDITION],
        );
    }

    /**
     * @param iterable<mixed, non-empty-string> $envVariables
     *
     * @return non-empty-string|null
     */
    private function tryGetEnvironmentAsString(iterable $envVariables): ?string
    {
        foreach ($envVariables as $name) {
            $server = $_SERVER[$name] ?? null;

            if (\is_string($server) && $server !== '') {
                return $server;
            }
        }

        return null;
    }

    public function createVendor(FamilyInterface $family): VendorInfo
    {
        $fallback = $this->delegate->createVendor($family);

        return new VendorInfo(
            name: $this->tryGetEnvironmentAsString($this->nameEnvVariableNames)
                ?? $fallback->name,
            version: $this->tryGetEnvironmentAsString($this->versionEnvVariableNames)
                ?? $fallback->version,
            codename: $this->tryGetEnvironmentAsString($this->codenameEnvVariableNames)
                ?? $fallback->codename,
            edition: $this->tryGetEnvironmentAsString($this->editionEnvVariableNames)
                ?? $fallback->edition,
        );
    }
}
