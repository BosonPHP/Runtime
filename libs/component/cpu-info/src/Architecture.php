<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo;

use Boson\Component\CpuInfo\Architecture\ArchitectureImpl;
use Boson\Component\CpuInfo\Architecture\Factory\DefaultArchitectureFactory;
use Boson\Component\CpuInfo\Architecture\Factory\InMemoryArchitectureFactory;

require_once __DIR__ . '/Architecture/constants.php';

/**
 * Main class representing CPU architecture information.
 *
 * Provides a set of predefined CPU architectures and methods to work
 * with them. It supports both built-in architectures (like x86, ARM, etc.)
 * and custom architectures.
 *
 * Implements enum-like structure representing predefined CPU architectures.
 *
 * Note: Impossible to implement via native PHP enum due to lack of support
 *       for properties: https://externals.io/message/126332
 */
final readonly class Architecture implements ArchitectureInterface
{
    use ArchitectureImpl;

    /**
     * Intel x86 architecture (32-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface x86 = Architecture\X86;

    /**
     * AMD64 architecture (64-bit x86)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Amd64 = Architecture\AMD64;

    /**
     * ARM architecture (32-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Arm = Architecture\ARM;

    /**
     * ARM64 architecture (64-bit ARM)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Arm64 = Architecture\ARM64;

    /**
     * Intel Itanium architecture
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Itanium = Architecture\ITANIUM;

    /**
     * RISC-V architecture (32-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface RiscV32 = Architecture\RISCV32;

    /**
     * RISC-V architecture (64-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface RiscV64 = Architecture\RISCV64;

    /**
     * MIPS architecture (32-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Mips = Architecture\MIPS;

    /**
     * MIPS architecture (64-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Mips64 = Architecture\MIPS64;

    /**
     * PowerPC architecture (32-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface PowerPc = Architecture\PPC;

    /**
     * PowerPC architecture (64-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface PowerPc64 = Architecture\PPC64;

    /**
     * SPARC architecture (32-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Sparc = Architecture\SPARC;

    /**
     * SPARC architecture (64-bit)
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const ArchitectureInterface Sparc64 = Architecture\SPARC64;

    /**
     * @var non-empty-array<non-empty-lowercase-string, ArchitectureInterface>
     */
    private const array CASES = [
        'x86' => Architecture::x86,
        'i386' => Architecture::x86,
        'ia32' => Architecture::x86,
        'amd64' => Architecture::Amd64,
        'x64' => Architecture::Amd64,
        'x86_64' => Architecture::Amd64,
        'arm64' => Architecture::Arm64,
        'aarch64' => Architecture::Arm64,
        'arm64ilp32' => Architecture::Arm64,
        'arm' => Architecture::Arm,
        'armel' => Architecture::Arm,
        'armhf' => Architecture::Arm,
        'mips' => Architecture::Mips,
        'mipsel' => Architecture::Mips,
        'mips64' => Architecture::Mips64,
        'mips64el' => Architecture::Mips64,
        'ppc' => Architecture::PowerPc,
        'powerpc' => Architecture::PowerPc,
        'powerpcspe' => Architecture::PowerPc,
        'ppc64' => Architecture::PowerPc64,
        'ppc64el' => Architecture::PowerPc64,
        'riscv64' => Architecture::RiscV64,
        'sparc' => Architecture::Sparc,
        'sparc64' => Architecture::Sparc64,
        'ia64' => Architecture::Itanium,
    ];

    /**
     * Creates a new architecture instance based on the current system.
     *
     * Note: The result is cached in memory for subsequent calls.
     *
     * @api
     */
    public static function createFromGlobals(): ArchitectureInterface
    {
        /** @phpstan-var InMemoryArchitectureFactory $factory */
        static $factory = new InMemoryArchitectureFactory(
            delegate: new DefaultArchitectureFactory(),
        );

        return $factory->createArchitecture();
    }

    /**
     * Translates a string value into the corresponding {@see Architecture}
     * case, if any. If there is no matching case defined,
     * it will return {@see null}.
     *
     * @api
     *
     * @param non-empty-string $value
     */
    public static function tryFrom(string $value): ?ArchitectureInterface
    {
        return self::CASES[\strtolower($value)] ?? null;
    }

    /**
     * Translates a string value into the corresponding {@see Architecture}
     * case, if any. If there is no matching case defined,
     * it will throw {@see \ValueError}.
     *
     * @api
     *
     * @param non-empty-string $value
     *
     * @throws \ValueError if there is no matching case defined
     */
    public static function from(string $value): ArchitectureInterface
    {
        return self::tryFrom($value)
            ?? throw new \ValueError(\sprintf(
                '"%s" is not a valid backing value for enum-like %s',
                $value,
                self::class,
            ));
    }

    /**
     * Return a packed {@see array} of all cases in an enumeration,
     * in order of declaration.
     *
     * @api
     *
     * @return non-empty-list<ArchitectureInterface>
     */
    public static function cases(): array
    {
        return \array_values(\array_unique(self::CASES));
    }
}
