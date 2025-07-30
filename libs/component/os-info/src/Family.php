<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo;

use Boson\Component\OsInfo\Family\Factory\DefaultFamilyFactory;
use Boson\Component\OsInfo\Family\Factory\InMemoryFamilyFactory;
use Boson\Component\OsInfo\Family\FamilyImpl;

require_once __DIR__ . '/Family/constants.php';

/**
 * Implements enum-like structure representing predefined
 * operating system families.
 *
 * Note: Impossible to implement via native PHP enum due to lack of support
 *       for properties: https://externals.io/message/126332
 */
final readonly class Family implements FamilyInterface
{
    use FamilyImpl;

    /**
     * Represents the Windows family of operating systems.
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const FamilyInterface Windows = Family\WINDOWS;

    /**
     * Represents the Linux family of operating systems.
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const FamilyInterface Linux = Family\LINUX;

    /**
     * Represents the Unix family of operating systems.
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const FamilyInterface Unix = Family\UNIX;

    /**
     * BSD operating system family.
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const FamilyInterface BSD = Family\BSD;

    /**
     * Solaris operating system family.
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const FamilyInterface Solaris = Family\SOLARIS;

    /**
     * Darwin operating system family.
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const FamilyInterface Darwin = Family\DARWIN;

    /**
     * @var non-empty-array<non-empty-lowercase-string, FamilyInterface>
     */
    private const array CASES = [
        'windows' => self::Windows,
        'unix' => self::Unix,
        'linux' => self::Linux,
        'bsd' => self::BSD,
        'solaris' => self::Solaris,
        'darwin' => self::Darwin,
    ];

    /**
     * @api
     */
    public static function createFromGlobals(): FamilyInterface
    {
        /** @phpstan-var InMemoryFamilyFactory $factory */
        static $factory = new InMemoryFamilyFactory(
            delegate: new DefaultFamilyFactory(),
        );

        return $factory->createFamily();
    }

    /**
     * Translates a string value into the corresponding {@see Family} case,
     * if any. If there is no matching case defined, it will return {@see null}.
     *
     * @api
     *
     * @param non-empty-string $value
     */
    public static function tryFrom(string $value): ?FamilyInterface
    {
        return self::CASES[\strtolower($value)] ?? null;
    }

    /**
     * Translates a string value into the corresponding {@see Family}
     * case, if any. If there is no matching case defined,
     * it will throw {@see \ValueError}.
     *
     * @api
     *
     * @param non-empty-string $value
     *
     * @throws \ValueError if there is no matching case defined
     */
    public static function from(string $value): FamilyInterface
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
     * @return non-empty-list<FamilyInterface>
     */
    public static function cases(): array
    {
        return \array_values(self::CASES);
    }
}
