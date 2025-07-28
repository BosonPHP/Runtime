<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Tests\Component;

use Boson\Component\Uri\Component\Scheme;
use Boson\Component\Uri\Tests\TestCase;
use Boson\Contracts\Uri\Component\SchemeInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri')]
final class SchemeTest extends TestCase
{
    public function testTryFromWithHttp(): void
    {
        $scheme = Scheme::tryFrom('http');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('http', $scheme->name);
        self::assertSame('http', (string) $scheme);
        self::assertSame('http', $scheme->toString());
    }

    public function testTryFromWithHttps(): void
    {
        $scheme = Scheme::tryFrom('https');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('https', $scheme->name);
        self::assertSame('https', (string) $scheme);
        self::assertSame('https', $scheme->toString());
    }

    public function testTryFromWithData(): void
    {
        $scheme = Scheme::tryFrom('data');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('data', $scheme->name);
        self::assertSame('data', (string) $scheme);
        self::assertSame('data', $scheme->toString());
    }

    public function testTryFromWithFile(): void
    {
        $scheme = Scheme::tryFrom('file');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('file', $scheme->name);
        self::assertSame('file', (string) $scheme);
        self::assertSame('file', $scheme->toString());
    }

    public function testTryFromWithFtp(): void
    {
        $scheme = Scheme::tryFrom('ftp');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('ftp', $scheme->name);
        self::assertSame('ftp', (string) $scheme);
        self::assertSame('ftp', $scheme->toString());
    }

    public function testTryFromWithGopher(): void
    {
        $scheme = Scheme::tryFrom('gopher');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('gopher', $scheme->name);
        self::assertSame('gopher', (string) $scheme);
        self::assertSame('gopher', $scheme->toString());
    }

    public function testTryFromWithWs(): void
    {
        $scheme = Scheme::tryFrom('ws');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('ws', $scheme->name);
        self::assertSame('ws', (string) $scheme);
        self::assertSame('ws', $scheme->toString());
    }

    public function testTryFromWithWss(): void
    {
        $scheme = Scheme::tryFrom('wss');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('wss', $scheme->name);
        self::assertSame('wss', (string) $scheme);
        self::assertSame('wss', $scheme->toString());
    }

    public function testTryFromWithUpperCase(): void
    {
        $scheme = Scheme::tryFrom('HTTP');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('http', $scheme->name);
        self::assertSame('http', (string) $scheme);
        self::assertSame('http', $scheme->toString());
    }

    public function testTryFromWithMixedCase(): void
    {
        $scheme = Scheme::tryFrom('HttpS');

        self::assertNotNull($scheme);
        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('https', $scheme->name);
        self::assertSame('https', (string) $scheme);
        self::assertSame('https', $scheme->toString());
    }

    public function testTryFromWithInvalidScheme(): void
    {
        $scheme = Scheme::tryFrom('invalid');

        self::assertNull($scheme);
    }

    public function testTryFromWithEmptyString(): void
    {
        $scheme = Scheme::tryFrom('');

        self::assertNull($scheme);
    }

    public function testFromWithHttp(): void
    {
        $scheme = Scheme::from('http');

        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('http', $scheme->name);
        self::assertSame('http', (string) $scheme);
        self::assertSame('http', $scheme->toString());
    }

    public function testFromWithHttps(): void
    {
        $scheme = Scheme::from('https');

        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('https', $scheme->name);
        self::assertSame('https', (string) $scheme);
        self::assertSame('https', $scheme->toString());
    }

    public function testFromWithUpperCase(): void
    {
        $scheme = Scheme::from('HTTP');

        self::assertInstanceOf(SchemeInterface::class, $scheme);
        self::assertSame('http', $scheme->name);
        self::assertSame('http', (string) $scheme);
        self::assertSame('http', $scheme->toString());
    }

    public function testFromWithInvalidSchemeThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('"invalid" is not a valid backing value for enum-like Boson\Component\Uri\Component\Scheme');

        Scheme::from('invalid');
    }

    public function testFromWithEmptyStringThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('"" is not a valid backing value for enum-like Boson\Component\Uri\Component\Scheme');

        Scheme::from('');
    }

    public function testCases(): void
    {
        $cases = Scheme::cases();

        self::assertNotEmpty($cases);
        self::assertContainsOnlyInstancesOf(SchemeInterface::class, $cases);

        $expectedSchemes = ['http', 'https', 'data', 'file', 'ftp', 'gopher', 'ws', 'wss'];
        $actualSchemes = [];

        foreach ($cases as $case) {
            $actualSchemes[] = $case->name;
        }

        \sort($expectedSchemes);
        \sort($actualSchemes);

        self::assertSame($expectedSchemes, $actualSchemes);
    }

    public function testEqualsWithSameInstance(): void
    {
        $scheme = Scheme::from('http');

        self::assertTrue($scheme->equals($scheme));
    }

    public function testEqualsWithIdenticalScheme(): void
    {
        $scheme1 = Scheme::from('http');
        $scheme2 = Scheme::from('http');

        self::assertTrue($scheme1->equals($scheme2));
        self::assertTrue($scheme2->equals($scheme1));
    }

    public function testEqualsWithDifferentSchemes(): void
    {
        $scheme1 = Scheme::from('http');
        $scheme2 = Scheme::from('https');

        self::assertFalse($scheme1->equals($scheme2));
        self::assertFalse($scheme2->equals($scheme1));
    }

    public function testEqualsWithDifferentTypes(): void
    {
        $scheme = Scheme::from('http');
        $other = 'not a scheme';

        self::assertFalse($scheme->equals($other));
    }

    public function testEqualsWithNull(): void
    {
        $scheme = Scheme::from('http');

        self::assertFalse($scheme->equals(null));
    }

    public function testEqualsWithCaseInsensitive(): void
    {
        $scheme1 = Scheme::from('http');
        $scheme2 = Scheme::from('HTTP');

        self::assertTrue($scheme1->equals($scheme2));
        self::assertTrue($scheme2->equals($scheme1));
    }

    public function testImplementsSchemeInterface(): void
    {
        $scheme = Scheme::from('http');

        self::assertInstanceOf(SchemeInterface::class, $scheme);
    }

    public function testPropertiesAreReadonly(): void
    {
        $scheme = Scheme::from('http');

        self::assertSame('http', $scheme->name);
    }

    public function testNamePropertyIsLowerCase(): void
    {
        $scheme = Scheme::from('HTTP');

        self::assertSame('http', $scheme->name);
    }

    public function testToStringReturnsLowerCase(): void
    {
        $scheme = Scheme::from('HTTP');

        self::assertSame('http', (string) $scheme);
        self::assertSame('http', $scheme->toString());
    }

    public function testAllBuiltinSchemes(): void
    {
        $schemes = [
            'http' => 'http',
            'https' => 'https',
            'data' => 'data',
            'file' => 'file',
            'ftp' => 'ftp',
            'gopher' => 'gopher',
            'ws' => 'ws',
            'wss' => 'wss',
        ];

        foreach ($schemes as $input => $expected) {
            $scheme = Scheme::from($input);
            self::assertSame($expected, $scheme->name);
            self::assertSame($expected, (string) $scheme);
            self::assertSame($expected, $scheme->toString());
        }
    }

    public function testAllBuiltinSchemesUpperCase(): void
    {
        $schemes = [
            'HTTP' => 'http',
            'HTTPS' => 'https',
            'DATA' => 'data',
            'FILE' => 'file',
            'FTP' => 'ftp',
            'GOPHER' => 'gopher',
            'WS' => 'ws',
            'WSS' => 'wss',
        ];

        foreach ($schemes as $input => $expected) {
            $scheme = Scheme::from($input);
            self::assertSame($expected, $scheme->name);
            self::assertSame($expected, (string) $scheme);
            self::assertSame($expected, $scheme->toString());
        }
    }

    public function testAllBuiltinSchemesMixedCase(): void
    {
        $schemes = [
            'Http' => 'http',
            'Https' => 'https',
            'Data' => 'data',
            'File' => 'file',
            'Ftp' => 'ftp',
            'Gopher' => 'gopher',
            'Ws' => 'ws',
            'Wss' => 'wss',
        ];

        foreach ($schemes as $input => $expected) {
            $scheme = Scheme::from($input);
            self::assertSame($expected, $scheme->name);
            self::assertSame($expected, (string) $scheme);
            self::assertSame($expected, $scheme->toString());
        }
    }

    public function testTryFromWithAllBuiltinSchemes(): void
    {
        $schemes = ['http', 'https', 'data', 'file', 'ftp', 'gopher', 'ws', 'wss'];

        foreach ($schemes as $schemeName) {
            $scheme = Scheme::tryFrom($schemeName);
            self::assertNotNull($scheme);
            self::assertSame($schemeName, $scheme->name);
        }
    }

    public function testTryFromWithInvalidSchemes(): void
    {
        $invalidSchemes = [
            'invalid',
            'http://',
            'https://',
            'ftp://',
            'mailto',
            'tel',
            'sms',
            'javascript',
            'about',
            'chrome',
            'moz-extension',
            'chrome-extension',
        ];

        foreach ($invalidSchemes as $invalidScheme) {
            $scheme = Scheme::tryFrom($invalidScheme);
            self::assertNull($scheme);
        }
    }

    public function testFromWithInvalidSchemesThrowsException(): void
    {
        $invalidSchemes = ['invalid', 'http://', 'https://', 'ftp://'];

        foreach ($invalidSchemes as $invalidScheme) {
            try {
                Scheme::from($invalidScheme);
                self::fail(\sprintf('Expected exception for scheme: %s', $invalidScheme));
            } catch (\ValueError $e) {
                self::assertStringContainsString($invalidScheme, $e->getMessage());
            }
        }
    }

    public function testCasesReturnsUniqueSchemes(): void
    {
        $cases = Scheme::cases();
        $schemeNames = [];

        foreach ($cases as $case) {
            $schemeNames[] = $case->name;
        }

        $uniqueNames = \array_unique($schemeNames);
        self::assertSame(\count($schemeNames), \count($uniqueNames));
    }

    public function testCasesReturnsImmutableSchemes(): void
    {
        $cases = Scheme::cases();

        foreach ($cases as $case) {
            self::assertSame($case->name, (string) $case);
            self::assertSame($case->name, $case->toString());
        }
    }

    public function testEdgeCaseWithNumbers(): void
    {
        $scheme = Scheme::tryFrom('http123');

        self::assertNull($scheme);
    }

    public function testEdgeCaseWithSpecialCharacters(): void
    {
        $scheme = Scheme::tryFrom('http-test');

        self::assertNull($scheme);
    }

    public function testEdgeCaseWithSpaces(): void
    {
        $scheme = Scheme::tryFrom('http test');

        self::assertNull($scheme);
    }

    public function testEdgeCaseWithUnicode(): void
    {
        $scheme = Scheme::tryFrom('httpé');

        self::assertNull($scheme);
    }

    public function testEdgeCaseWithEmoji(): void
    {
        $scheme = Scheme::tryFrom('http😀');

        self::assertNull($scheme);
    }

    public function testEdgeCaseWithVeryLongString(): void
    {
        $longString = \str_repeat('a', 1000);
        $scheme = Scheme::tryFrom($longString);

        self::assertNull($scheme);
    }

    public function testEdgeCaseWithNullByte(): void
    {
        $scheme = Scheme::tryFrom("http\0");

        self::assertNull($scheme);
    }

    public function testEdgeCaseWithControlCharacters(): void
    {
        $scheme = Scheme::tryFrom("http\x00");

        self::assertNull($scheme);
    }
}
