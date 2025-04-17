<?php

declare(strict_types=1);

namespace Serafim\Boson\WebView\Internal\WebViewCreateInfo;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Serafim\Boson\WebView
 */
final readonly class FlagsListFormatter
{
    private function __construct() {}

    /**
     * Converts a list of flags into a normalized string representation.
     *
     * @param iterable<array-key, string|float|bool|int|list<string|float|bool|int>> $flags
     *
     * @return iterable<array-key, non-empty-string>
     */
    public static function format(iterable $flags): iterable
    {
        foreach ($flags as $name => $value) {
            if (!\is_string($value)) {
                $result = self::formatFlagValue($name, $value);

                if ($result !== '') {
                    yield $result;
                }

                continue;
            }

            yield \vsprintf('%s=%s', [
                $name = self::formatFlagName($name),
                self::formatFlagValue($name, $value),
            ]);
        }
    }

    /**
     * @return non-empty-string
     *
     * @phpstan-return ($name is non-empty-string ? non-empty-string : never)
     */
    private static function formatFlagName(string $name): string
    {
        $name = \trim($name);

        if ($name === '') {
            throw new \InvalidArgumentException('WebView flag name cannot be empty');
        }

        return $name;
    }

    /**
     * @param array-key $name
     */
    private static function formatFlagValue(int|string $name, mixed $value): string
    {
        return match (true) {
            $value === true => 'true',
            $value === false => 'false',
            \is_string($value) => \var_export(\trim($value), true),
            \is_float($value),
            \is_int($value) => \var_export($value, true),
            \is_array($value) => self::formatFlagsListValue($name, $value),
            default => throw new \InvalidArgumentException(\sprintf(
                'Flag "%s" contain an invalid value of type %s',
                $name,
                \get_debug_type($value),
            )),
        };
    }

    /**
     * @param array-key $name
     * @param array<array-key, mixed> $values
     */
    private static function formatFlagsListValue(int|string $name, array $values): string
    {
        $result = [];

        foreach ($values as $value) {
            $formatted = self::formatFlagValue($name, $value);

            $result[] = \addcslashes($formatted, ',');
        }

        return \implode(',', $result);
    }
}
