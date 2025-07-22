<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Vendor\VendorInfo;

/**
 * Returns general (and imprecise) OS information
 */
final readonly class GenericVendorFactory implements VendorFactoryInterface
{
    public function createVendor(FamilyInterface $family): VendorInfo
    {
        return new VendorInfo(
            name: self::getDefaultName(),
            version: self::getDefaultVersion(),
        );
    }

    /**
     * @return non-empty-string
     */
    public static function getDefaultName(): string
    {
        /** @var non-empty-string */
        return \php_uname('s');
    }

    /**
     * @return non-empty-string
     */
    public static function getDefaultVersion(): string
    {
        $version = \php_uname('r');

        /** @var non-empty-string */
        return self::tryParseVersion($version)
            ?? $version;
    }

    /**
     * @return non-empty-string|null
     */
    public static function tryParseVersion(string $version): ?string
    {
        \preg_match('/^\d+(?:\.\d+){0,3}/', $version, $matches);

        /** @var non-empty-string|null */
        return $matches[0] ?? null;
    }
}
