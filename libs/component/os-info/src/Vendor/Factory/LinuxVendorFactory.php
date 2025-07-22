<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory;

use Boson\Component\OsInfo\Family;
use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Vendor\VendorInfo;

final readonly class LinuxVendorFactory implements OptionalVendorFactoryInterface
{
    /**
     * @var non-empty-string
     */
    private const string OS_RELEASE_PATHNAME = '/etc/os-release';

    /**
     * @var non-empty-string
     */
    private const string OS_RELEASE_NAME = 'NAME';

    /**
     * @var non-empty-string
     */
    private const string OS_RELEASE_VERSION = 'VERSION_ID';

    /**
     * @var non-empty-string
     */
    private const string OS_RELEASE_CODENAME = 'VERSION_CODENAME';

    /**
     * @var non-empty-string
     */
    private const string OS_RELEASE_CODENAME_FROM_VERSION = 'VERSION';

    public function __construct(
        /**
         * @var non-empty-string
         */
        private string $osReleasePathname = self::OS_RELEASE_PATHNAME,
    ) {}

    public function createVendor(FamilyInterface $family): ?VendorInfo
    {
        if (!$family->is(Family::Unix) || !\is_readable($this->osReleasePathname)) {
            return null;
        }

        /** @var array<non-empty-string, string> $info */
        $info = (array) @\parse_ini_file($this->osReleasePathname);

        $name = $this->fetchName($info)
            ?? GenericVendorFactory::getDefaultName();

        $version = $this->fetchVersion($info)
            ?? GenericVendorFactory::getDefaultVersion();

        return new VendorInfo(
            name: $name,
            version: $version,
            codename: $this->fetchCodename($info),
        );
    }

    /**
     * @param array<non-empty-string, string> $info
     *
     * @return non-empty-string|null
     */
    private function fetchCodename(array $info): ?string
    {
        return $this->fetchCodenameFromVersion($info)
            ?? $this->fetchRawCodename($info)
            ?? null;
    }

    /**
     * @param array<non-empty-string, string> $info
     *
     * @return non-empty-string|null
     */
    private function fetchRawCodename(array $info): ?string
    {
        $rawCodename = $info[self::OS_RELEASE_CODENAME] ?? '';

        return $rawCodename === '' ? null : $rawCodename;
    }

    /**
     * @param array<non-empty-string, string> $info
     *
     * @return non-empty-string|null
     */
    private function fetchCodenameFromVersion(array $info): ?string
    {
        $version = $info[self::OS_RELEASE_CODENAME_FROM_VERSION] ?? '';

        if ($version === '') {
            return null;
        }

        \preg_match('/\((.+?)\)$/u', $version, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param array<non-empty-string, string> $info
     *
     * @return non-empty-string|null
     */
    private function fetchVersion(array $info): ?string
    {
        return $this->fetchParsedVersion($info)
            ?? $this->fetchRawVersion($info)
            ?? null;
    }

    /**
     * @param array<non-empty-string, string> $info
     *
     * @return non-empty-string|null
     */
    private function fetchRawVersion(array $info): ?string
    {
        $rawVersion = $info[self::OS_RELEASE_VERSION] ?? '';

        return $rawVersion === '' ? null : $rawVersion;
    }

    /**
     * @param array<non-empty-string, string> $info
     *
     * @return non-empty-string|null
     */
    private function fetchParsedVersion(array $info): ?string
    {
        $parsedVersion = GenericVendorFactory::tryParseVersion(
            version: $info[self::OS_RELEASE_VERSION] ?? '',
        );

        return $parsedVersion === '' ? null : $parsedVersion;
    }

    /**
     * @param array<non-empty-string, string> $info
     *
     * @return non-empty-string|null
     */
    private function fetchName(array $info): ?string
    {
        $name = $info[self::OS_RELEASE_NAME] ?? '';

        return $name === '' ? null : $name;
    }
}
