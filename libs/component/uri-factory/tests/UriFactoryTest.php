<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Tests;

use Boson\Component\Uri\Factory\UriFactory;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri-factory')]
final class UriFactoryTest extends TestCase
{
    private UriFactory $factory;

    #[Before]
    protected function createPathFactory(): void
    {
        $this->factory = new UriFactory();
    }

    public function testCreateUriFromStringWithFullUri(): void
    {
        $uri = $this->factory->createUriFromString('https://user:pass@host:8080/path/to/resource?foo=bar#frag');

        self::assertSame('https', $uri->scheme?->toString());
        self::assertSame('host', $uri->authority?->host);
        self::assertSame(8080, $uri->authority?->port);
        self::assertSame('user', $uri->authority?->userInfo?->user);
        self::assertSame('pass', $uri->authority?->userInfo?->password);
        self::assertSame('/path/to/resource', $uri->path->toString());
        self::assertSame('foo=bar', $uri->query->toString());
        self::assertSame('frag', $uri->fragment);
    }

    public function testCreateUriFromStringWithMinimalUri(): void
    {
        $uri = $this->factory->createUriFromString('/');

        self::assertNull($uri->scheme);
        self::assertNull($uri->authority);
        self::assertSame('/', $uri->path->toString());
        self::assertSame('', $uri->query->toString());
        self::assertNull($uri->fragment);
    }

    public function testCreateUriFromStringWithInvalidUri(): void
    {
        $uri = $this->factory->createUriFromString('!@#$%^&*()');

        self::assertNull($uri->scheme);
        self::assertNull($uri->authority);
        self::assertSame('%21%40', $uri->path->toString());
        self::assertSame('', $uri->query->toString());
        self::assertSame('$%^&*()', $uri->fragment);
    }
}
