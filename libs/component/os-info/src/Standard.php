<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo;

use Boson\Component\OsInfo\Standard\Factory\DefaultStandardsFactory;
use Boson\Component\OsInfo\Standard\Factory\InMemoryStandardsFactory;
use Boson\Component\OsInfo\Standard\StandardImpl;

require_once __DIR__ . '/Standard/constants.php';

/**
 * Implements enum-like structure representing predefined operating
 * system standards.
 *
 * Note: Impossible to implement via native PHP enum due to lack of support
 *       for properties: https://externals.io/message/126332
 */
final readonly class Standard implements StandardInterface
{
    use StandardImpl;

    /**
     * POSIX operating system standard.
     *
     * @link https://posix.opengroup.org/
     * @link https://standards.ieee.org/ieee/1003.1/7700/
     * @link https://www.iso.org/standard/50516.html
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StandardInterface Posix = Standard\POSIX;

    /**
     * @var non-empty-array<non-empty-lowercase-string, StandardInterface>
     */
    private const array CASES = [
        'posix' => self::Posix,
    ];

    /**
     * @api
     *
     * @return list<StandardInterface>
     */
    public static function createFromGlobals(): array
    {
        /** @phpstan-var InMemoryStandardsFactory $factory */
        static $factory = new InMemoryStandardsFactory(
            delegate: new DefaultStandardsFactory(),
        );

        return $factory->createStandards(Family::createFromGlobals());
    }

    /**
     * Translates a string value into the corresponding {@see Standard} case,
     * if any. If there is no matching case defined, it will return {@see null}.
     *
     * @api
     *
     * @param non-empty-string $value
     */
    public static function tryFrom(string $value): ?StandardInterface
    {
        return self::CASES[\strtolower($value)] ?? null;
    }

    /**
     * Translates a string value into the corresponding {@see Standard}
     * case, if any. If there is no matching case defined,
     * it will throw {@see \ValueError}.
     *
     * @api
     *
     * @param non-empty-string $value
     *
     * @throws \ValueError if there is no matching case defined
     */
    public static function from(string $value): StandardInterface
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
     * @return non-empty-list<StandardInterface>
     */
    public static function cases(): array
    {
        return \array_values(self::CASES);
    }
}
