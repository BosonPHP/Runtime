<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo;

use Boson\Component\CpuInfo\InstructionSet\InstructionSetImpl;

require_once __DIR__ . '/InstructionSet/constants.php';

/**
 * Main class representing CPU instruction sets.
 *
 * Provides a set of predefined CPU instruction sets and methods to work
 * with them. It supports both built-in instruction sets (like MMX, SSE,
 * AVX, etc.) and custom instruction sets.
 */
final class InstructionSet implements InstructionSetInterface
{
    use InstructionSetImpl;

    /**
     * MultiMedia eXtensions instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface MMX = InstructionSet\MMX;

    /**
     * Streaming SIMD Extensions instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface SSE = InstructionSet\SSE;

    /**
     * Streaming SIMD Extensions 2 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface SSE2 = InstructionSet\SSE2;

    /**
     * Streaming SIMD Extensions 3 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface SSE3 = InstructionSet\SSE3;

    /**
     * Supplemental Streaming SIMD Extensions 3 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface SSSE3 = InstructionSet\SSSE3;

    /**
     * Streaming SIMD Extensions 4.1 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface SSE4_1 = InstructionSet\SSE4_1;

    /**
     * Streaming SIMD Extensions 4.2 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface SSE4_2 = InstructionSet\SSE4_2;

    /**
     * Fused Multiply-Add 3 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface FMA3 = InstructionSet\FMA3;

    /**
     * Advanced Vector Extensions instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface AVX = InstructionSet\AVX;

    /**
     * Advanced Vector Extensions 2 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface AVX2 = InstructionSet\AVX2;

    /**
     * Advanced Vector Extensions 512 instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface AVX512F = InstructionSet\AVX512F;

    /**
     * Advanced Encryption Standard New Instructions (AES-NI) instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface AES = InstructionSet\AES;

    /**
     * Intel Extended Memory 64 Technology (EM64T) instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface EM64T = InstructionSet\EM64T;

    /**
     * Population Count (POPCNT) instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface POPCNT = InstructionSet\POPCNT;

    /**
     * Half-Precision Floating-Point Conversion (F16C) instruction set
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const InstructionSetInterface F16C = InstructionSet\F16C;

    /**
     * @var non-empty-array<non-empty-lowercase-string, InstructionSetInterface>
     */
    private const array CASES = [
        'mmx' => self::MMX,
        'sse' => self::SSE,
        'sse2' => self::SSE2,
        'pni' => self::SSE3,
        'sse3' => self::SSE3,
        'ssse3' => self::SSSE3,
        'sse4_1' => self::SSE4_1,
        'sse4_2' => self::SSE4_2,
        'avx' => self::AVX,
        'avx2' => self::AVX2,
        'avx512f' => self::AVX512F,
        'aes' => self::AES,
        'em64t' => self::EM64T,
        'popcnt' => self::POPCNT,
        'f16c' => self::F16C,
    ];

    /**
     * Translates a string value into the corresponding {@see InstructionSet}
     * case, if any. If there is no matching case defined,
     * it will return {@see null}.
     *
     * @api
     *
     * @param non-empty-string $value
     */
    public static function tryFrom(string $value): ?InstructionSetInterface
    {
        return self::CASES[\strtolower($value)] ?? null;
    }

    /**
     * Translates a string value into the corresponding {@see InstructionSet}
     * case, if any. If there is no matching case defined,
     * it will throw {@see \ValueError}.
     *
     * @api
     *
     * @param non-empty-string $value
     *
     * @throws \ValueError if there is no matching case defined
     */
    public static function from(string $value): InstructionSetInterface
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
     * @return non-empty-list<InstructionSetInterface>
     */
    public static function cases(): array
    {
        return \array_values(self::CASES);
    }
}
