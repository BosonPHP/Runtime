<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Tests\Component\Scheme;

use Boson\Component\Uri\Component\Scheme\StandardScheme;
use Boson\Component\Uri\Tests\TestCase;
use Boson\Contracts\Uri\Component\SchemeInterface;

#[Group('boson-php/runtime')]
final class StandardSchemeTest extends TestCase
{
    public function testConstructorWithNameOnly(): void
    {
        $scheme = new StandardScheme('custom');

        self::assertSame('custom', $scheme->name);
        self::assertSame('custom', (string) $scheme);
        self::assertSame('custom', $scheme->toString());
        self::assertNull($scheme->port);
    }

    public function testConstructorWithNameAndPort(): void
    {
        $scheme = new StandardScheme('custom', 8080);

        self::assertSame('custom', $scheme->name);
        self::assertSame('custom', (string) $scheme);
        self::assertSame('custom', $scheme->toString());
        self::assertSame(8080, $scheme->port);
    }

    public function testConstructorWithUpperCaseName(): void
    {
        $scheme = new StandardScheme('CUSTOM');

        self::assertSame('custom', $scheme->name);
        self::assertSame('custom', (string) $scheme);
        self::assertSame('custom', $scheme->toString());
    }

    public function testConstructorWithMixedCaseName(): void
    {
        $scheme = new StandardScheme('CustomScheme');

        self::assertSame('customscheme', $scheme->name);
        self::assertSame('customscheme', (string) $scheme);
        self::assertSame('customscheme', $scheme->toString());
    }

    public function testConstructorWithPortZero(): void
    {
        $scheme = new StandardScheme('custom', 0);

        self::assertSame('custom', $scheme->name);
        self::assertSame(0, $scheme->port);
    }

    public function testConstructorWithPortMaxValue(): void
    {
        $scheme = new StandardScheme('custom', 65535);

        self::assertSame('custom', $scheme->name);
        self::assertSame(65535, $scheme->port);
    }

    public function testEqualsWithSameInstance(): void
    {
        $scheme = new StandardScheme('custom', 8080);

        self::assertTrue($scheme->equals($scheme));
    }

    public function testEqualsWithIdenticalScheme(): void
    {
        $scheme1 = new StandardScheme('custom', 8080);
        $scheme2 = new StandardScheme('custom', 8080);

        self::assertTrue($scheme1->equals($scheme2));
        self::assertTrue($scheme2->equals($scheme1));
    }

    public function testEqualsWithDifferentName(): void
    {
        $scheme1 = new StandardScheme('custom1', 8080);
        $scheme2 = new StandardScheme('custom2', 8080);

        self::assertFalse($scheme1->equals($scheme2));
        self::assertFalse($scheme2->equals($scheme1));
    }

    public function testEqualsWithDifferentPort(): void
    {
        $scheme1 = new StandardScheme('custom', 8080);
        $scheme2 = new StandardScheme('custom', 9090);

        self::assertTrue($scheme1->equals($scheme2));
        self::assertTrue($scheme2->equals($scheme1));
    }

    public function testEqualsWithPortVsNoPort(): void
    {
        $scheme1 = new StandardScheme('custom', 8080);
        $scheme2 = new StandardScheme('custom');

        self::assertTrue($scheme1->equals($scheme2));
        self::assertTrue($scheme2->equals($scheme1));
    }

    public function testEqualsWithCaseInsensitiveName(): void
    {
        $scheme1 = new StandardScheme('custom', 8080);
        $scheme2 = new StandardScheme('CUSTOM', 8080);

        self::assertTrue($scheme1->equals($scheme2));
        self::assertTrue($scheme2->equals($scheme1));
    }

    public function testEqualsWithDifferentTypes(): void
    {
        $scheme = new StandardScheme('custom', 8080);
        $other = 'not a scheme';

        self::assertFalse($scheme->equals($other));
    }

    public function testEqualsWithNull(): void
    {
        $scheme = new StandardScheme('custom', 8080);

        self::assertFalse($scheme->equals(null));
    }

    public function testImplementsSchemeInterface(): void
    {
        $scheme = new StandardScheme('custom', 8080);

        self::assertInstanceOf(SchemeInterface::class, $scheme);
    }

    public function testPropertiesAreReadonly(): void
    {
        $scheme = new StandardScheme('custom', 8080);

        self::assertSame('custom', $scheme->name);
        self::assertSame(8080, $scheme->port);
    }

    public function testNamePropertyIsLowerCase(): void
    {
        $scheme = new StandardScheme('CUSTOM', 8080);

        self::assertSame('custom', $scheme->name);
    }

    public function testToStringReturnsLowerCase(): void
    {
        $scheme = new StandardScheme('CUSTOM', 8080);

        self::assertSame('custom', (string) $scheme);
        self::assertSame('custom', $scheme->toString());
    }

    public function testEdgeCaseEmptyName(): void
    {
        $scheme = new StandardScheme('');

        self::assertSame('', $scheme->name);
        self::assertSame('', (string) $scheme);
        self::assertSame('', $scheme->toString());
        self::assertNull($scheme->port);
    }

    public function testEdgeCaseEmptyNameWithPort(): void
    {
        $scheme = new StandardScheme('', 8080);

        self::assertSame('', $scheme->name);
        self::assertSame('', (string) $scheme);
        self::assertSame('', $scheme->toString());
        self::assertSame(8080, $scheme->port);
    }

    public function testEdgeCaseNameWithSpecialCharacters(): void
    {
        $scheme = new StandardScheme('custom-scheme');

        self::assertSame('custom-scheme', $scheme->name);
        self::assertSame('custom-scheme', (string) $scheme);
        self::assertSame('custom-scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithUnderscores(): void
    {
        $scheme = new StandardScheme('custom_scheme');

        self::assertSame('custom_scheme', $scheme->name);
        self::assertSame('custom_scheme', (string) $scheme);
        self::assertSame('custom_scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithNumbers(): void
    {
        $scheme = new StandardScheme('custom123');

        self::assertSame('custom123', $scheme->name);
        self::assertSame('custom123', (string) $scheme);
        self::assertSame('custom123', $scheme->toString());
    }

    public function testEdgeCaseNameWithPlusSign(): void
    {
        $scheme = new StandardScheme('custom+scheme');

        self::assertSame('custom+scheme', $scheme->name);
        self::assertSame('custom+scheme', (string) $scheme);
        self::assertSame('custom+scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithPeriod(): void
    {
        $scheme = new StandardScheme('custom.scheme');

        self::assertSame('custom.scheme', $scheme->name);
        self::assertSame('custom.scheme', (string) $scheme);
        self::assertSame('custom.scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithUnicode(): void
    {
        $scheme = new StandardScheme('customé');

        self::assertSame('customé', $scheme->name);
        self::assertSame('customé', (string) $scheme);
        self::assertSame('customé', $scheme->toString());
    }

    public function testEdgeCaseNameWithEmoji(): void
    {
        $scheme = new StandardScheme('custom😀');

        self::assertSame('custom😀', $scheme->name);
        self::assertSame('custom😀', (string) $scheme);
        self::assertSame('custom😀', $scheme->toString());
    }

    public function testEdgeCaseVeryLongName(): void
    {
        $longName = \str_repeat('a', 1000);
        $scheme = new StandardScheme($longName, 8080);

        self::assertSame($longName, $scheme->name);
        self::assertSame($longName, (string) $scheme);
        self::assertSame($longName, $scheme->toString());
        self::assertSame(8080, $scheme->port);
    }

    public function testEdgeCaseNullByteInName(): void
    {
        $scheme = new StandardScheme("custom\0", 8080);

        self::assertSame("custom\0", $scheme->name);
        self::assertSame("custom\0", (string) $scheme);
        self::assertSame("custom\0", $scheme->toString());
        self::assertSame(8080, $scheme->port);
    }

    public function testEdgeCaseControlCharactersInName(): void
    {
        $scheme = new StandardScheme("custom\x00\x01\x02", 8080);

        self::assertSame("custom\x00\x01\x02", $scheme->name);
        self::assertSame("custom\x00\x01\x02", (string) $scheme);
        self::assertSame("custom\x00\x01\x02", $scheme->toString());
        self::assertSame(8080, $scheme->port);
    }

    public function testEdgeCaseNameWithSpaces(): void
    {
        $scheme = new StandardScheme('custom scheme');

        self::assertSame('custom scheme', $scheme->name);
        self::assertSame('custom scheme', (string) $scheme);
        self::assertSame('custom scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithColon(): void
    {
        $scheme = new StandardScheme('custom:scheme');

        self::assertSame('custom:scheme', $scheme->name);
        self::assertSame('custom:scheme', (string) $scheme);
        self::assertSame('custom:scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithAtSign(): void
    {
        $scheme = new StandardScheme('custom@scheme');

        self::assertSame('custom@scheme', $scheme->name);
        self::assertSame('custom@scheme', (string) $scheme);
        self::assertSame('custom@scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithSlash(): void
    {
        $scheme = new StandardScheme('custom/scheme');

        self::assertSame('custom/scheme', $scheme->name);
        self::assertSame('custom/scheme', (string) $scheme);
        self::assertSame('custom/scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithQuestionMark(): void
    {
        $scheme = new StandardScheme('custom?scheme');

        self::assertSame('custom?scheme', $scheme->name);
        self::assertSame('custom?scheme', (string) $scheme);
        self::assertSame('custom?scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithHash(): void
    {
        $scheme = new StandardScheme('custom#scheme');

        self::assertSame('custom#scheme', $scheme->name);
        self::assertSame('custom#scheme', (string) $scheme);
        self::assertSame('custom#scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithBrackets(): void
    {
        $scheme = new StandardScheme('custom[scheme]');

        self::assertSame('custom[scheme]', $scheme->name);
        self::assertSame('custom[scheme]', (string) $scheme);
        self::assertSame('custom[scheme]', $scheme->toString());
    }

    public function testEdgeCaseNameWithBraces(): void
    {
        $scheme = new StandardScheme('custom{scheme}');

        self::assertSame('custom{scheme}', $scheme->name);
        self::assertSame('custom{scheme}', (string) $scheme);
        self::assertSame('custom{scheme}', $scheme->toString());
    }

    public function testEdgeCaseNameWithParentheses(): void
    {
        $scheme = new StandardScheme('custom(scheme)');

        self::assertSame('custom(scheme)', $scheme->name);
        self::assertSame('custom(scheme)', (string) $scheme);
        self::assertSame('custom(scheme)', $scheme->toString());
    }

    public function testEdgeCaseNameWithLessThan(): void
    {
        $scheme = new StandardScheme('custom<scheme');

        self::assertSame('custom<scheme', $scheme->name);
        self::assertSame('custom<scheme', (string) $scheme);
        self::assertSame('custom<scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithGreaterThan(): void
    {
        $scheme = new StandardScheme('custom>scheme');

        self::assertSame('custom>scheme', $scheme->name);
        self::assertSame('custom>scheme', (string) $scheme);
        self::assertSame('custom>scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithPipe(): void
    {
        $scheme = new StandardScheme('custom|scheme');

        self::assertSame('custom|scheme', $scheme->name);
        self::assertSame('custom|scheme', (string) $scheme);
        self::assertSame('custom|scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithBackslash(): void
    {
        $scheme = new StandardScheme('custom\\scheme');

        self::assertSame('custom\\scheme', $scheme->name);
        self::assertSame('custom\\scheme', (string) $scheme);
        self::assertSame('custom\\scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithCaret(): void
    {
        $scheme = new StandardScheme('custom^scheme');

        self::assertSame('custom^scheme', $scheme->name);
        self::assertSame('custom^scheme', (string) $scheme);
        self::assertSame('custom^scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithTilde(): void
    {
        $scheme = new StandardScheme('custom~scheme');

        self::assertSame('custom~scheme', $scheme->name);
        self::assertSame('custom~scheme', (string) $scheme);
        self::assertSame('custom~scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithSemicolon(): void
    {
        $scheme = new StandardScheme('custom;scheme');

        self::assertSame('custom;scheme', $scheme->name);
        self::assertSame('custom;scheme', (string) $scheme);
        self::assertSame('custom;scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithComma(): void
    {
        $scheme = new StandardScheme('custom,scheme');

        self::assertSame('custom,scheme', $scheme->name);
        self::assertSame('custom,scheme', (string) $scheme);
        self::assertSame('custom,scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithDollar(): void
    {
        $scheme = new StandardScheme('custom$scheme');

        self::assertSame('custom$scheme', $scheme->name);
        self::assertSame('custom$scheme', (string) $scheme);
        self::assertSame('custom$scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithExclamation(): void
    {
        $scheme = new StandardScheme('custom!scheme');

        self::assertSame('custom!scheme', $scheme->name);
        self::assertSame('custom!scheme', (string) $scheme);
        self::assertSame('custom!scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithAsterisk(): void
    {
        $scheme = new StandardScheme('custom*scheme');

        self::assertSame('custom*scheme', $scheme->name);
        self::assertSame('custom*scheme', (string) $scheme);
        self::assertSame('custom*scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithSingleQuote(): void
    {
        $scheme = new StandardScheme("custom'scheme");

        self::assertSame("custom'scheme", $scheme->name);
        self::assertSame("custom'scheme", (string) $scheme);
        self::assertSame("custom'scheme", $scheme->toString());
    }

    public function testEdgeCaseNameWithDoubleQuote(): void
    {
        $scheme = new StandardScheme('custom"scheme');

        self::assertSame('custom"scheme', $scheme->name);
        self::assertSame('custom"scheme', (string) $scheme);
        self::assertSame('custom"scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithPercent(): void
    {
        $scheme = new StandardScheme('custom%scheme');

        self::assertSame('custom%scheme', $scheme->name);
        self::assertSame('custom%scheme', (string) $scheme);
        self::assertSame('custom%scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithAmpersand(): void
    {
        $scheme = new StandardScheme('custom&scheme');

        self::assertSame('custom&scheme', $scheme->name);
        self::assertSame('custom&scheme', (string) $scheme);
        self::assertSame('custom&scheme', $scheme->toString());
    }

    public function testEdgeCaseNameWithEqualsSign(): void
    {
        $scheme = new StandardScheme('custom=scheme');

        self::assertSame('custom=scheme', $scheme->name);
        self::assertSame('custom=scheme', (string) $scheme);
        self::assertSame('custom=scheme', $scheme->toString());
    }

    public function testEdgeCaseMultipleSpecialCharacters(): void
    {
        $scheme = new StandardScheme('custom-scheme+with.multiple@special#chars', 8080);

        self::assertSame('custom-scheme+with.multiple@special#chars', $scheme->name);
        self::assertSame('custom-scheme+with.multiple@special#chars', (string) $scheme);
        self::assertSame('custom-scheme+with.multiple@special#chars', $scheme->toString());
        self::assertSame(8080, $scheme->port);
    }
}
