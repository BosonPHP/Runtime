<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Tests\Component;

use Boson\Component\Uri\Component\Path;
use Boson\Component\Uri\Tests\TestCase;
use Boson\Contracts\Uri\Component\PathInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri')]
final class PathTest extends TestCase
{
    public function testConstructorWithEmptySegments(): void
    {
        $path = new Path();

        self::assertSame(0, $path->count());
        self::assertSame('/', (string) $path);
        self::assertSame('/', $path->toString());
    }

    public function testConstructorWithSingleSegment(): void
    {
        $path = new Path(['api']);

        self::assertSame(1, $path->count());
        self::assertSame('/api', (string) $path);
        self::assertSame('/api', $path->toString());
    }

    public function testConstructorWithMultipleSegments(): void
    {
        $path = new Path(['api', 'users', '123']);

        self::assertSame(3, $path->count());
        self::assertSame('/api/users/123', (string) $path);
        self::assertSame('/api/users/123', $path->toString());
    }

    public function testConstructorWithEmptyStringSegments(): void
    {
        $path = new Path(['', 'api', '']);

        self::assertSame(3, $path->count());
        self::assertSame('//api/', (string) $path);
        self::assertSame('//api/', $path->toString());
    }

    public function testConstructorWithIterator(): void
    {
        $iterator = new \ArrayIterator(['api', 'users']);
        $path = new Path($iterator);

        self::assertSame(2, $path->count());
        self::assertSame('/api/users', (string) $path);
        self::assertSame('/api/users', $path->toString());
    }

    public function testConstructorWithGenerator(): void
    {
        $generator = (function (): \Generator {
            yield 'api';
            yield 'users';
            yield '123';
        })();
        $path = new Path($generator);

        self::assertSame(3, $path->count());
        self::assertSame('/api/users/123', (string) $path);
        self::assertSame('/api/users/123', $path->toString());
    }

    public function testGetIterator(): void
    {
        $segments = ['api', 'users', '123'];
        $path = new Path($segments);

        $iterator = $path->getIterator();
        self::assertInstanceOf(\ArrayIterator::class, $iterator);

        $result = [];
        foreach ($iterator as $segment) {
            $result[] = $segment;
        }

        self::assertSame($segments, $result);
    }

    public function testIteration(): void
    {
        $segments = ['api', 'users', '123'];
        $path = new Path($segments);

        $result = [];
        foreach ($path as $segment) {
            $result[] = $segment;
        }

        self::assertSame($segments, $result);
    }

    public function testCount(): void
    {
        $path = new Path(['api', 'users', '123']);

        self::assertSame(3, $path->count());
        self::assertSame(3, \count($path));
    }

    public function testCountWithEmptyPath(): void
    {
        $path = new Path();

        self::assertSame(0, $path->count());
        self::assertSame(0, \count($path));
    }

    public function testEqualsWithSameInstance(): void
    {
        $path = new Path(['api', 'users']);

        self::assertTrue($path->equals($path));
    }

    public function testEqualsWithIdenticalPath(): void
    {
        $path1 = new Path(['api', 'users', '123']);
        $path2 = new Path(['api', 'users', '123']);

        self::assertTrue($path1->equals($path2));
        self::assertTrue($path2->equals($path1));
    }

    public function testEqualsWithDifferentSegments(): void
    {
        $path1 = new Path(['api', 'users']);
        $path2 = new Path(['api', 'posts']);

        self::assertFalse($path1->equals($path2));
        self::assertFalse($path2->equals($path1));
    }

    public function testEqualsWithDifferentSegmentCount(): void
    {
        $path1 = new Path(['api', 'users']);
        $path2 = new Path(['api', 'users', '123']);

        self::assertFalse($path1->equals($path2));
        self::assertFalse($path2->equals($path1));
    }

    public function testEqualsWithDifferentTypes(): void
    {
        $path = new Path(['api', 'users']);
        $other = 'not a path';

        self::assertFalse($path->equals($other));
    }

    public function testEqualsWithNull(): void
    {
        $path = new Path(['api', 'users']);

        self::assertFalse($path->equals(null));
    }

    public function testEqualsWithEmptyPaths(): void
    {
        $path1 = new Path();
        $path2 = new Path();

        self::assertTrue($path1->equals($path2));
        self::assertTrue($path2->equals($path1));
    }

    public function testEqualsWithEmptyVsNonEmptyPath(): void
    {
        $path1 = new Path();
        $path2 = new Path(['api']);

        self::assertFalse($path1->equals($path2));
        self::assertFalse($path2->equals($path1));
    }

    public function testImplementsPathInterface(): void
    {
        $path = new Path(['api', 'users']);

        self::assertInstanceOf(PathInterface::class, $path);
    }

    public function testImplementsIteratorAggregate(): void
    {
        $path = new Path(['api', 'users']);

        self::assertInstanceOf(\IteratorAggregate::class, $path);
    }

    public function testImplementsCountable(): void
    {
        $path = new Path(['api', 'users']);

        self::assertInstanceOf(\Countable::class, $path);
    }

    public function testUrlEncodeSegments(): void
    {
        $path = new Path(['api', 'user name', 'user@domain']);

        self::assertSame('/api/user%20name/user%40domain', (string) $path);
        self::assertSame('/api/user%20name/user%40domain', $path->toString());
    }

    public function testUrlEncodeSpecialCharacters(): void
    {
        $path = new Path(['api', 'user/name', 'user?param=value']);

        self::assertSame('/api/user%2Fname/user%3Fparam%3Dvalue', (string) $path);
        self::assertSame('/api/user%2Fname/user%3Fparam%3Dvalue', $path->toString());
    }

    public function testUrlEncodeUnicodeCharacters(): void
    {
        $path = new Path(['api', 'пользователь', 'résumé']);

        self::assertSame('/api/%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C/r%C3%A9sum%C3%A9', (string) $path);
        self::assertSame('/api/%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C/r%C3%A9sum%C3%A9', $path->toString());
    }

    public function testUrlEncodeEmoji(): void
    {
        $path = new Path(['api', 'user😀name']);

        self::assertSame('/api/user%F0%9F%98%80name', (string) $path);
        self::assertSame('/api/user%F0%9F%98%80name', $path->toString());
    }

    public function testUrlEncodePercentSign(): void
    {
        $path = new Path(['api', 'user%name']);

        self::assertSame('/api/user%25name', (string) $path);
        self::assertSame('/api/user%25name', $path->toString());
    }

    public function testUrlEncodePlusSign(): void
    {
        $path = new Path(['api', 'user+name']);

        self::assertSame('/api/user%2Bname', (string) $path);
        self::assertSame('/api/user%2Bname', $path->toString());
    }

    public function testUrlEncodeHashSign(): void
    {
        $path = new Path(['api', 'user#name']);

        self::assertSame('/api/user%23name', (string) $path);
        self::assertSame('/api/user%23name', $path->toString());
    }

    public function testUrlEncodeAmpersand(): void
    {
        $path = new Path(['api', 'user&name']);

        self::assertSame('/api/user%26name', (string) $path);
        self::assertSame('/api/user%26name', $path->toString());
    }

    public function testUrlEncodeEqualsSign(): void
    {
        $path = new Path(['api', 'user=name']);

        self::assertSame('/api/user%3Dname', (string) $path);
        self::assertSame('/api/user%3Dname', $path->toString());
    }

    public function testUrlEncodeBrackets(): void
    {
        $path = new Path(['api', 'user[name]']);

        self::assertSame('/api/user%5Bname%5D', (string) $path);
        self::assertSame('/api/user%5Bname%5D', $path->toString());
    }

    public function testUrlEncodeBraces(): void
    {
        $path = new Path(['api', 'user{name}']);

        self::assertSame('/api/user%7Bname%7D', (string) $path);
        self::assertSame('/api/user%7Bname%7D', $path->toString());
    }

    public function testUrlEncodePipe(): void
    {
        $path = new Path(['api', 'user|name']);

        self::assertSame('/api/user%7Cname', (string) $path);
        self::assertSame('/api/user%7Cname', $path->toString());
    }

    public function testUrlEncodeBackslash(): void
    {
        $path = new Path(['api', 'user\\name']);

        self::assertSame('/api/user%5Cname', (string) $path);
        self::assertSame('/api/user%5Cname', $path->toString());
    }

    public function testUrlEncodeCaret(): void
    {
        $path = new Path(['api', 'user^name']);

        self::assertSame('/api/user%5Ename', (string) $path);
        self::assertSame('/api/user%5Ename', $path->toString());
    }

    /**
     * @link https://datatracker.ietf.org/doc/html/rfc3986#section-2.3
     */
    public function testUrlEncodeTilde(): void
    {
        $path = new Path(['api', 'user~name']);

        self::assertSame('/api/user~name', (string) $path);
        self::assertSame('/api/user~name', $path->toString());
    }

    public function testUrlEncodeSemicolon(): void
    {
        $path = new Path(['api', 'user;name']);

        self::assertSame('/api/user%3Bname', (string) $path);
        self::assertSame('/api/user%3Bname', $path->toString());
    }

    public function testUrlEncodeComma(): void
    {
        $path = new Path(['api', 'user,name']);

        self::assertSame('/api/user%2Cname', (string) $path);
        self::assertSame('/api/user%2Cname', $path->toString());
    }

    public function testUrlEncodeDollar(): void
    {
        $path = new Path(['api', 'user$name']);

        self::assertSame('/api/user%24name', (string) $path);
        self::assertSame('/api/user%24name', $path->toString());
    }

    public function testUrlEncodeExclamation(): void
    {
        $path = new Path(['api', 'user!name']);

        self::assertSame('/api/user%21name', (string) $path);
        self::assertSame('/api/user%21name', $path->toString());
    }

    public function testUrlEncodeAsterisk(): void
    {
        $path = new Path(['api', 'user*name']);

        self::assertSame('/api/user%2Aname', (string) $path);
        self::assertSame('/api/user%2Aname', $path->toString());
    }

    public function testUrlEncodeSingleQuote(): void
    {
        $path = new Path(['api', "user'name"]);

        self::assertSame('/api/user%27name', (string) $path);
        self::assertSame('/api/user%27name', $path->toString());
    }

    public function testUrlEncodeDoubleQuote(): void
    {
        $path = new Path(['api', 'user"name']);

        self::assertSame('/api/user%22name', (string) $path);
        self::assertSame('/api/user%22name', $path->toString());
    }

    public function testUrlEncodeParentheses(): void
    {
        $path = new Path(['api', 'user(name)']);

        self::assertSame('/api/user%28name%29', (string) $path);
        self::assertSame('/api/user%28name%29', $path->toString());
    }

    public function testUrlEncodeLessThan(): void
    {
        $path = new Path(['api', 'user<name']);

        self::assertSame('/api/user%3Cname', (string) $path);
        self::assertSame('/api/user%3Cname', $path->toString());
    }

    public function testUrlEncodeGreaterThan(): void
    {
        $path = new Path(['api', 'user>name']);

        self::assertSame('/api/user%3Ename', (string) $path);
        self::assertSame('/api/user%3Ename', $path->toString());
    }

    public function testUrlEncodeMultipleSpecialCharacters(): void
    {
        $path = new Path(['api', 'user name@domain.com']);

        self::assertSame('/api/user%20name%40domain.com', (string) $path);
        self::assertSame('/api/user%20name%40domain.com', $path->toString());
    }

    public function testEdgeCaseSingleSlash(): void
    {
        $path = new Path(['']);

        self::assertSame(1, $path->count());
        self::assertSame('/', (string) $path);
        self::assertSame('/', $path->toString());
    }

    public function testEdgeCaseMultipleEmptySegments(): void
    {
        $path = new Path(['', '', '']);

        self::assertSame(3, $path->count());
        self::assertSame('///', (string) $path);
        self::assertSame('///', $path->toString());
    }

    public function testEdgeCaseMixedEmptyAndNonEmptySegments(): void
    {
        $path = new Path(['', 'api', '', 'users', '']);

        self::assertSame(5, $path->count());
        self::assertSame('//api//users/', (string) $path);
        self::assertSame('//api//users/', $path->toString());
    }

    public function testEdgeCaseNumericSegments(): void
    {
        $path = new Path(['api', '123', '456']);

        self::assertSame(3, $path->count());
        self::assertSame('/api/123/456', (string) $path);
        self::assertSame('/api/123/456', $path->toString());
    }

    public function testEdgeCaseMixedTypes(): void
    {
        $path = new Path(['api', '123', 'user-name', '']);

        self::assertSame(4, $path->count());
        self::assertSame('/api/123/user-name/', (string) $path);
        self::assertSame('/api/123/user-name/', $path->toString());
    }

    public function testPropertiesAreReadonly(): void
    {
        $path = new Path(['api', 'users']);

        self::assertSame(2, $path->count());
        self::assertSame('/api/users', (string) $path);
    }
}
