<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Tests;

use Boson\Component\Uri\Component\Authority;
use Boson\Component\Uri\Component\Path;
use Boson\Component\Uri\Component\Query;
use Boson\Component\Uri\Component\Scheme;
use Boson\Component\Uri\Component\UserInfo;
use Boson\Component\Uri\Uri;
use Boson\Contracts\Uri\UriInterface;

#[Group('boson-php/runtime')]
final class UriTest extends TestCase
{
    public function testConstructorWithPathOnly(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertSame($path, $uri->path);
        self::assertInstanceOf(Query::class, $uri->query);
        self::assertSame(0, $uri->query->count());
        self::assertNull($uri->scheme);
        self::assertNull($uri->authority);
        self::assertNull($uri->fragment);
        self::assertNull($uri->user);
        self::assertNull($uri->password);
        self::assertNull($uri->host);
        self::assertNull($uri->port);
    }

    public function testConstructorWithAllComponents(): void
    {
        $path = new Path(['api', 'users']);
        $query = new Query(['page' => '1']);
        $scheme = Scheme::from('https');
        $userInfo = new UserInfo('username', 'password');
        $authority = new Authority('example.com', 8080, $userInfo);
        $fragment = 'section1';

        $uri = new Uri($path, $query, $scheme, $authority, $fragment);

        self::assertSame($path, $uri->path);
        self::assertSame($query, $uri->query);
        self::assertSame($scheme, $uri->scheme);
        self::assertSame($authority, $uri->authority);
        self::assertSame($fragment, $uri->fragment);
        self::assertSame('username', $uri->user);
        self::assertSame('password', $uri->password);
        self::assertSame('example.com', $uri->host);
        self::assertSame(8080, $uri->port);
    }

    public function testConstructorWithSchemeAndAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $scheme = Scheme::from('http');
        $authority = new Authority('example.com', 80);

        $uri = new Uri($path, new Query(), $scheme, $authority);

        self::assertSame($path, $uri->path);
        self::assertSame($scheme, $uri->scheme);
        self::assertSame($authority, $uri->authority);
        self::assertNull($uri->fragment);
        self::assertNull($uri->user);
        self::assertNull($uri->password);
        self::assertSame('example.com', $uri->host);
        self::assertSame(80, $uri->port);
    }

    public function testConstructorWithFragmentOnly(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = 'section1';

        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame($path, $uri->path);
        self::assertNull($uri->scheme);
        self::assertNull($uri->authority);
        self::assertSame($fragment, $uri->fragment);
        self::assertNull($uri->user);
        self::assertNull($uri->password);
        self::assertNull($uri->host);
        self::assertNull($uri->port);
    }

    public function testToStringWithPathOnly(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertSame('/api/users', (string) $uri);
        self::assertSame('/api/users', $uri->toString());
    }

    public function testToStringWithScheme(): void
    {
        $path = new Path(['api', 'users']);
        $scheme = Scheme::from('https');
        $uri = new Uri($path, new Query(), $scheme);

        self::assertSame('https:/api/users', (string) $uri);
        self::assertSame('https:/api/users', $uri->toString());
    }

    public function testToStringWithAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $authority = new Authority('example.com', 8080);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertSame('//example.com:8080/api/users', (string) $uri);
        self::assertSame('//example.com:8080/api/users', $uri->toString());
    }

    public function testToStringWithSchemeAndAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $scheme = Scheme::from('https');
        $authority = new Authority('example.com', 443);
        $uri = new Uri($path, new Query(), $scheme, $authority);

        self::assertSame('https://example.com:443/api/users', (string) $uri);
        self::assertSame('https://example.com:443/api/users', $uri->toString());
    }

    public function testToStringWithQuery(): void
    {
        $path = new Path(['api', 'users']);
        $query = new Query(['page' => '1', 'limit' => '10']);
        $uri = new Uri($path, $query);

        self::assertSame('/api/users?page=1&limit=10', (string) $uri);
        self::assertSame('/api/users?page=1&limit=10', $uri->toString());
    }

    public function testToStringWithFragment(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = 'section1';
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame('/api/users#section1', (string) $uri);
        self::assertSame('/api/users#section1', $uri->toString());
    }

    public function testToStringWithAllComponents(): void
    {
        $path = new Path(['api', 'users']);
        $query = new Query(['page' => '1']);
        $scheme = Scheme::from('https');
        $userInfo = new UserInfo('username', 'password');
        $authority = new Authority('example.com', 8080, $userInfo);
        $fragment = 'section1';

        $uri = new Uri($path, $query, $scheme, $authority, $fragment);

        self::assertSame('https://username:password@example.com:8080/api/users?page=1#section1', (string) $uri);
        self::assertSame('https://username:password@example.com:8080/api/users?page=1#section1', $uri->toString());
    }

    public function testToStringWithEmptyQuery(): void
    {
        $path = new Path(['api', 'users']);
        $query = new Query();
        $uri = new Uri($path, $query);

        self::assertSame('/api/users', (string) $uri);
        self::assertSame('/api/users', $uri->toString());
    }

    public function testToStringWithEmptyPath(): void
    {
        $path = new Path();
        $uri = new Uri($path);

        self::assertSame('/', (string) $uri);
        self::assertSame('/', $uri->toString());
    }

    public function testToStringWithEmptyPathAndAuthority(): void
    {
        $path = new Path();
        $authority = new Authority('example.com', 80);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertSame('//example.com:80/', (string) $uri);
        self::assertSame('//example.com:80/', $uri->toString());
    }

    public function testToStringWithFragmentContainingSpecialCharacters(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = 'section with spaces';
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame('/api/users#section%20with%20spaces', (string) $uri);
        self::assertSame('/api/users#section%20with%20spaces', $uri->toString());
    }

    public function testToStringWithFragmentContainingUnicode(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = 'секция';
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame('/api/users#%D1%81%D0%B5%D0%BA%D1%86%D0%B8%D1%8F', (string) $uri);
        self::assertSame('/api/users#%D1%81%D0%B5%D0%BA%D1%86%D0%B8%D1%8F', $uri->toString());
    }

    public function testEqualsWithSameInstance(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertTrue($uri->equals($uri));
    }

    public function testEqualsWithIdenticalUri(): void
    {
        $path = new Path(['api', 'users']);
        $query = new Query(['page' => '1']);
        $scheme = Scheme::from('https');
        $authority = new Authority('example.com', 443);
        $fragment = 'section1';

        $uri1 = new Uri($path, $query, $scheme, $authority, $fragment);
        $uri2 = new Uri($path, $query, $scheme, $authority, $fragment);

        self::assertTrue($uri1->equals($uri2));
        self::assertTrue($uri2->equals($uri1));
    }

    public function testEqualsWithDifferentPath(): void
    {
        $path1 = new Path(['api', 'users']);
        $path2 = new Path(['api', 'posts']);
        $uri1 = new Uri($path1);
        $uri2 = new Uri($path2);

        self::assertFalse($uri1->equals($uri2));
        self::assertFalse($uri2->equals($uri1));
    }

    public function testEqualsWithDifferentQuery(): void
    {
        $path = new Path(['api', 'users']);
        $query1 = new Query(['page' => '1']);
        $query2 = new Query(['page' => '2']);
        $uri1 = new Uri($path, $query1);
        $uri2 = new Uri($path, $query2);

        self::assertFalse($uri1->equals($uri2));
        self::assertFalse($uri2->equals($uri1));
    }

    public function testEqualsWithDifferentScheme(): void
    {
        $path = new Path(['api', 'users']);
        $scheme1 = Scheme::from('http');
        $scheme2 = Scheme::from('https');
        $uri1 = new Uri($path, new Query(), $scheme1);
        $uri2 = new Uri($path, new Query(), $scheme2);

        self::assertFalse($uri1->equals($uri2));
        self::assertFalse($uri2->equals($uri1));
    }

    public function testEqualsWithDifferentAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $authority1 = new Authority('example.com', 80);
        $authority2 = new Authority('example.org', 80);
        $uri1 = new Uri($path, new Query(), null, $authority1);
        $uri2 = new Uri($path, new Query(), null, $authority2);

        self::assertFalse($uri1->equals($uri2));
        self::assertFalse($uri2->equals($uri1));
    }

    public function testEqualsWithDifferentFragment(): void
    {
        $path = new Path(['api', 'users']);
        $uri1 = new Uri($path, new Query(), null, null, 'section1');
        $uri2 = new Uri($path, new Query(), null, null, 'section2');

        self::assertFalse($uri1->equals($uri2));
        self::assertFalse($uri2->equals($uri1));
    }

    public function testEqualsWithNullVsNotNullFragment(): void
    {
        $path = new Path(['api', 'users']);
        $uri1 = new Uri($path, new Query(), null, null, null);
        $uri2 = new Uri($path, new Query(), null, null, 'section1');

        self::assertFalse($uri1->equals($uri2));
        self::assertFalse($uri2->equals($uri1));
    }

    public function testEqualsWithDifferentTypes(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);
        $other = 'not a uri';

        self::assertFalse($uri->equals($other));
    }

    public function testEqualsWithNull(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertFalse($uri->equals(null));
    }

    public function testImplementsUriInterface(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertInstanceOf(UriInterface::class, $uri);
    }

    public function testPropertiesAreReadonly(): void
    {
        $path = new Path(['api', 'users']);
        $query = new Query(['page' => '1']);
        $scheme = Scheme::from('https');
        $authority = new Authority('example.com', 443);
        $fragment = 'section1';

        $uri = new Uri($path, $query, $scheme, $authority, $fragment);

        self::assertSame($path, $uri->path);
        self::assertSame($query, $uri->query);
        self::assertSame($scheme, $uri->scheme);
        self::assertSame($authority, $uri->authority);
        self::assertSame($fragment, $uri->fragment);
    }

    public function testUserPropertyDelegatesToAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $userInfo = new UserInfo('testuser', 'testpass');
        $authority = new Authority('example.com', 8080, $userInfo);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertSame('testuser', $uri->user);
    }

    public function testPasswordPropertyDelegatesToAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $userInfo = new UserInfo('testuser', 'testpass');
        $authority = new Authority('example.com', 8080, $userInfo);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertSame('testpass', $uri->password);
    }

    public function testHostPropertyDelegatesToAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $authority = new Authority('example.com', 8080);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertSame('example.com', $uri->host);
    }

    public function testPortPropertyDelegatesToAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $authority = new Authority('example.com', 8080);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertSame(8080, $uri->port);
    }

    public function testUserPropertyReturnsNullWhenNoAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertNull($uri->user);
    }

    public function testPasswordPropertyReturnsNullWhenNoAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertNull($uri->password);
    }

    public function testHostPropertyReturnsNullWhenNoAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertNull($uri->host);
    }

    public function testPortPropertyReturnsNullWhenNoAuthority(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path);

        self::assertNull($uri->port);
    }

    public function testUserPropertyReturnsNullWhenAuthorityHasNoUserInfo(): void
    {
        $path = new Path(['api', 'users']);
        $authority = new Authority('example.com', 8080);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertNull($uri->user);
    }

    public function testPasswordPropertyReturnsNullWhenAuthorityHasNoUserInfo(): void
    {
        $path = new Path(['api', 'users']);
        $authority = new Authority('example.com', 8080);
        $uri = new Uri($path, new Query(), null, $authority);

        self::assertNull($uri->password);
    }

    public function testEdgeCaseEmptyFragment(): void
    {
        $path = new Path(['api', 'users']);
        $uri = new Uri($path, new Query(), null, null, '');

        self::assertSame('', $uri->fragment);
        self::assertSame('/api/users#', (string) $uri);
        self::assertSame('/api/users#', $uri->toString());
    }

    public function testEdgeCaseFragmentWithSpecialCharacters(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = 'section/with/slashes?and=query#hash';
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame($fragment, $uri->fragment);
        self::assertSame('/api/users#section%2Fwith%2Fslashes%3Fand%3Dquery%23hash', (string) $uri);
        self::assertSame('/api/users#section%2Fwith%2Fslashes%3Fand%3Dquery%23hash', $uri->toString());
    }

    public function testEdgeCaseFragmentWithUnicode(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = 'секция с пробелами';
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame($fragment, $uri->fragment);
        self::assertSame('/api/users#%D1%81%D0%B5%D0%BA%D1%86%D0%B8%D1%8F%20%D1%81%20%D0%BF%D1%80%D0%BE%D0%B1%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8', (string) $uri);
        self::assertSame('/api/users#%D1%81%D0%B5%D0%BA%D1%86%D0%B8%D1%8F%20%D1%81%20%D0%BF%D1%80%D0%BE%D0%B1%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8', $uri->toString());
    }

    public function testEdgeCaseFragmentWithEmoji(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = 'section😀with😀emoji';
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame($fragment, $uri->fragment);
        self::assertSame('/api/users#section%F0%9F%98%80with%F0%9F%98%80emoji', (string) $uri);
        self::assertSame('/api/users#section%F0%9F%98%80with%F0%9F%98%80emoji', $uri->toString());
    }

    public function testEdgeCaseVeryLongFragment(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = \str_repeat('a', 1000);
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame($fragment, $uri->fragment);
        self::assertStringContainsString($fragment, (string) $uri);
        self::assertStringContainsString($fragment, $uri->toString());
    }

    public function testEdgeCaseNullByteInFragment(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = "section\0with\0null\0bytes";
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame($fragment, $uri->fragment);
        self::assertStringContainsString('%00', (string) $uri);
        self::assertStringContainsString('%00', $uri->toString());
    }

    public function testEdgeCaseControlCharactersInFragment(): void
    {
        $path = new Path(['api', 'users']);
        $fragment = "section\x00\x01\x02with\x03\x04\x05control";
        $uri = new Uri($path, new Query(), null, null, $fragment);

        self::assertSame($fragment, $uri->fragment);
        self::assertStringContainsString('%00', (string) $uri);
        self::assertStringContainsString('%01', (string) $uri);
        self::assertStringContainsString('%02', (string) $uri);
        self::assertStringContainsString('%03', (string) $uri);
        self::assertStringContainsString('%04', (string) $uri);
        self::assertStringContainsString('%05', (string) $uri);
    }

    public function testComplexUriExample(): void
    {
        $path = new Path(['api', 'v1', 'users', '123']);
        $query = new Query(['include' => ['profile', 'posts'], 'page' => '1', 'limit' => '10']);
        $scheme = Scheme::from('https');
        $userInfo = new UserInfo('admin', 'secret123');
        $authority = new Authority('api.example.com', 443, $userInfo);
        $fragment = 'user-details';

        $uri = new Uri($path, $query, $scheme, $authority, $fragment);

        $expected = 'https://admin:secret123@api.example.com:443/api/v1/users/123?include%5B0%5D=profile&include%5B1%5D=posts&page=1&limit=10#user-details';

        self::assertSame($expected, (string) $uri);
        self::assertSame($expected, $uri->toString());
        self::assertSame('admin', $uri->user);
        self::assertSame('secret123', $uri->password);
        self::assertSame('api.example.com', $uri->host);
        self::assertSame(443, $uri->port);
    }
}
