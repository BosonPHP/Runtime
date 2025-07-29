<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Tests;

use Boson\Component\Uri\Component\Scheme;
use Boson\Component\Uri\Factory\Component\UriSchemeFactory;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri-factory')]
final class SchemeFactoryTest extends TestCase
{
    private UriSchemeFactory $factory;

    #[Before]
    protected function createPathFactory(): void
    {
        $this->factory = new UriSchemeFactory();
    }

    public function testCreateSchemeFromStringWithStandardScheme(): void
    {
        $scheme = $this->factory->createSchemeFromString('HTTP');

        self::assertSame('http', $scheme->toString());
    }

    public function testCreateSchemeFromStringWithUserDefinedScheme(): void
    {
        $scheme = $this->factory->createSchemeFromString('custom-scheme');

        self::assertInstanceOf(Scheme::class, $scheme);
        self::assertSame('custom-scheme', $scheme->toString());
    }
}
