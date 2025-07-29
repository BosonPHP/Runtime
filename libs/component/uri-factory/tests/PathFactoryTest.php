<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Tests;

use Boson\Component\Uri\Factory\Component\UriPathFactory;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri-factory')]
final class PathFactoryTest extends TestCase
{
    private UriPathFactory $factory;

    #[Before]
    protected function createPathFactory(): void
    {
        $this->factory = new UriPathFactory();
    }

    public function testCreatePathFromStringWithSimplePath(): void
    {
        $path = $this->factory->createPathFromString('/api/v1');

        self::assertSame('/api/v1', $path->toString());
    }

    public function testCreatePathFromStringWithEmptyPath(): void
    {
        $path = $this->factory->createPathFromString('');

        self::assertSame('', $path->toString());
    }

    public function testCreatePathFromStringWithMultipleDelimiters(): void
    {
        $path = $this->factory->createPathFromString('///a//b///c/');

        self::assertSame('/a/b/c/', $path->toString());
    }

    public function testCreatePathFromStringWithEncodedSegments(): void
    {
        $path = $this->factory->createPathFromString('/foo%20bar/baz');

        self::assertSame('/foo%20bar/baz', $path->toString());
    }
}
