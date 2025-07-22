<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory;

use Boson\Component\OsInfo\Family;
use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Vendor\VendorInfo;

final readonly class MacOSVendorFactory implements OptionalVendorFactoryInterface
{
    /**
     * @var non-empty-string
     */
    private const string SYS_VERSION_PATHNAME = '/System/Library/CoreServices/SystemVersion.plist';

    /**
     * @var non-empty-string
     */
    private const string SYS_NAME_PCRE = '/<key>ProductName<\/key>\n\h*<string>(.+?)<\/string>/';

    /**
     * @var non-empty-string
     */
    private const string SYS_VERSION_PCRE = '/<key>ProductVersion<\/key>\n\h*<string>(.+?)<\/string>/';

    /**
     * @var non-empty-string
     */
    private const string SYS_LICENSE_PATHNAME = '/System/Library/CoreServices/Setup\ Assistant.app/Contents/Resources/en.lproj/OSXSoftwareLicense.rtf';

    /**
     * @link https://github.com/wazuh/wazuh/blob/v4.13.0-alpha1/src/shared/version_op.c#L364
     * @link https://en.wikipedia.org/wiki/List_of_Apple_codenames#Mac_OS_X_/_OS_X_/_macOS
     *
     * @var non-empty-array<int, non-empty-array<int, non-empty-string>|non-empty-string>
     */
    private const array BUILTIN_VERSION_TO_CODENAME = [
        10 => [
            0 => 'Cheetah',
            1 => 'Puma',
            2 => 'Jaguar',
            3 => 'Panther',
            4 => 'Tiger',
            5 => 'Leopard',
            6 => 'Snow Leopard',
            7 => 'Lion',
            8 => 'Mountain Lion',
            9 => 'Mavericks',
            10 => 'Yosemite',
            11 => 'El Capitan',
            12 => 'Sierra',
            13 => 'High Sierra',
            14 => 'Mojave',
            15 => 'Catalina',
        ],
        11 => 'Big Sur',
        12 => 'Monterey',
        13 => 'Ventura',
        14 => 'Sonoma',
        15 => 'Sequoia',
    ];

    public function __construct(
        /**
         * @var non-empty-string
         */
        private string $systemVersionPathname = self::SYS_VERSION_PATHNAME,
        /**
         * @var non-empty-string
         */
        private string $osxLicensePathname = self::SYS_LICENSE_PATHNAME,
    ) {}

    public function createVendor(FamilyInterface $family): ?VendorInfo
    {
        // Expects Darwin-like OS
        if (!$family->is(Family::Darwin) && \is_readable($this->systemVersionPathname)) {
            return null;
        }

        $systemVersion = (string) @\file_get_contents($this->systemVersionPathname);

        $name = $this->fetchName($systemVersion)
            ?? GenericVendorFactory::getDefaultName();

        $version = $this->fetchVersion($systemVersion)
            ?? GenericVendorFactory::getDefaultVersion();

        return new VendorInfo(
            name: $name,
            version: $version,
            codename: $this->fetchCodename($version),
        );
    }

    /**
     * @return non-empty-string|null
     */
    private function fetchName(string $info): ?string
    {
        if (!\str_starts_with($info, '<?xml')) {
            return null;
        }

        \preg_match(self::SYS_NAME_PCRE, $info, $matches);

        if (isset($matches[1]) && $matches[1] !== '') {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return non-empty-string|null
     */
    private function fetchVersion(string $info): ?string
    {
        if (!\str_starts_with($info, '<?xml')) {
            return null;
        }

        \preg_match(self::SYS_VERSION_PCRE, $info, $matches);

        if (isset($matches[1]) && $matches[1] !== '') {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param non-empty-string $version
     *
     * @return non-empty-string|null
     */
    private function fetchCodename(string $version): ?string
    {
        return $this->fetchCodenameFromLicense()
            ?? $this->fetchCodenameFromBuiltinList($version);
    }

    /**
     * @link https://unix.stackexchange.com/questions/234104/get-osx-codename-from-command-line/458401
     *
     * @return non-empty-string|null
     */
    private function fetchCodenameFromLicense(): ?string
    {
        if (!\is_readable($this->osxLicensePathname)) {
            return null;
        }

        $license = (string) @\file_get_contents($this->osxLicensePathname);

        \preg_match('/SOFTWARE LICENSE AGREEMENT FOR (?:OS X|macOS)\h+(.+?)\\\/isum', $license, $matches);

        if (isset($matches[1]) && $matches[1] !== '') {
            return $matches[1];
        }

        return null;
    }

    /**
     * @link https://github.com/wazuh/wazuh/blob/v4.13.0-alpha1/src/shared/version_op.c#L364
     * @link https://en.wikipedia.org/wiki/List_of_Apple_codenames#Mac_OS_X_/_OS_X_/_macOS
     *
     * @return non-empty-string|null
     */
    private function fetchCodenameFromBuiltinList(string $version): ?string
    {
        \preg_match('/^(?P<major>\d+)(?P<minor>\.\d+)?/', $version, $segments);

        $codenames = self::BUILTIN_VERSION_TO_CODENAME[
            (int) ($segments['major'] ?? $version)
        ] ?? null;

        if (!\is_array($codenames)) {
            return $codenames;
        }

        return $codenames[(int) ($segments['minor'] ?? 0)] ?? null;
    }
}
