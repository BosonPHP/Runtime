<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\Tests;

use Boson\Component\CpuInfo\ArchitectureInterface;
use Boson\Component\CpuInfo\InstructionSetInterface;
use Boson\Component\CpuInfo\InstructionSet\Factory\CpuId\DetectorInterface;
use Boson\Component\CpuInfo\Architecture\Factory\ArchitectureFactoryInterface;
use Boson\Component\CpuInfo\InstructionSet\Factory\InstructionSetFactoryInterface;
use Boson\Component\CpuInfo\InstructionSet\Factory\OptionalInstructionSetFactoryInterface;
use Boson\Component\CpuInfo\Factory\CentralProcessorFactoryInterface;
use Boson\Component\CpuInfo\Vendor\Factory\VendorFactoryInterface;
use Boson\Component\Pasm\ExecutorInterface;
use Boson\Component\CpuInfo\CentralProcessor;
use Boson\Component\CpuInfo\Vendor\VendorInfo;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/cpu-info')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testArchitectureInterfaceCompatibility(): void
    {
        new class implements ArchitectureInterface {
            public string $name {
                get {}
            }
            public ?ArchitectureInterface $parent {
                get {}
            }
            public function is(ArchitectureInterface $arch): bool {}
            public function __toString(): string {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testInstructionSetInterfaceCompatibility(): void
    {
        new class implements InstructionSetInterface {
            public string $name {
                get {}
            }
            public function __toString(): string {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testDetectorInterfaceCompatibility(): void
    {
        new class implements DetectorInterface {
            public function isSupported(ArchitectureInterface $arch): bool {}
            public function detect(ExecutorInterface $executor): ?InstructionSetInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testArchitectureFactoryInterfaceCompatibility(): void
    {
        new class implements ArchitectureFactoryInterface {
            public function createArchitecture(): ArchitectureInterface {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testInstructionSetFactoryInterfaceCompatibility(): void
    {
        new class implements InstructionSetFactoryInterface {
            public function createInstructionSets(ArchitectureInterface $arch): iterable {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testOptionalInstructionSetFactoryInterfaceCompatibility(): void
    {
        new class implements OptionalInstructionSetFactoryInterface {
            public function createInstructionSets(ArchitectureInterface $arch): ?iterable {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testCentralProcessorFactoryInterfaceCompatibility(): void
    {
        new class implements CentralProcessorFactoryInterface {
            public function createCentralProcessor(): CentralProcessor {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testVendorFactoryInterfaceCompatibility(): void
    {
        new class implements VendorFactoryInterface {
            public function createVendor(): VendorInfo {}
        };
    }
}
