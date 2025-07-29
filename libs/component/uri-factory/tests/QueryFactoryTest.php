<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Tests;

use Boson\Component\Uri\Factory\Component\UriQueryFactory;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri-factory')]
final class QueryFactoryTest extends TestCase
{
    private UriQueryFactory $factory;

    #[Before]
    protected function createPathFactory(): void
    {
        $this->factory = new UriQueryFactory();
    }

    public function testCreateQueryFromStringWithSimpleQuery(): void
    {
        $query = $this->factory->createQueryFromString('foo=bar&baz=qux');

        self::assertSame('foo=bar&baz=qux', $query->toString());
    }

    public function testCreateQueryFromStringWithEmptyQuery(): void
    {
        $query = $this->factory->createQueryFromString('');

        self::assertSame('', $query->toString());
    }

    public function testCreateQueryFromStringWithArrayParameter(): void
    {
        $query = $this->factory->createQueryFromString('tags[]=php&tags[]=test');

        self::assertSame('tags%5B0%5D=php&tags%5B1%5D=test', $query->toString());
    }
}
