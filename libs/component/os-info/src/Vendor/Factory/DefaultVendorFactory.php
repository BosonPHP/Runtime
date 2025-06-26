<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Vendor\Factory;

use Boson\Component\OsInfo\FamilyInterface;
use Boson\Component\OsInfo\Vendor\VendorInfo;

final readonly class DefaultVendorFactory implements VendorFactoryInterface
{
    private OptionalVendorFactoryInterface $default;

    public function __construct()
    {
        $this->default = EnvVendorFactory::createForOverrideEnvVariables(
            delegate: new CompoundVendorFactory(
                default: new GenericVendorFactory(),
                factories: [
                    new LinuxVendorFactory(),
                    new Win32VendorFactory(),
                    new MacOSVendorFactory(),
                ],
            ),
        );
    }

    public function createVendor(FamilyInterface $family): VendorInfo
    {
        return $this->default->createVendor($family);
    }
}
