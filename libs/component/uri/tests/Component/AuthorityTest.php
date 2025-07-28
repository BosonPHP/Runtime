<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Tests\Component;

use Boson\Component\Uri\Component\Authority;
use Boson\Component\Uri\Component\UserInfo;
use Boson\Component\Uri\Tests\TestCase;
use Boson\Contracts\Uri\Component\AuthorityInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri')]
final class AuthorityTest extends TestCase
{
    public function testConstructorWithHostOnly(): void
    {
        $authority = new Authority('example.com');

        self::assertSame('example.com', $authority->host);
        self::assertNull($authority->port);
        self::assertNull($authority->userInfo);
        self::assertNull($authority->user);
        self::assertNull($authority->password);
    }

    public function testConstructorWithHostAndPort(): void
    {
        $authority = new Authority('example.com', 8080);

        self::assertSame('example.com', $authority->host);
        self::assertSame(8080, $authority->port);
        self::assertNull($authority->userInfo);
        self::assertNull($authority->user);
        self::assertNull($authority->password);
    }

    public function testConstructorWithHostPortAndUserInfo(): void
    {
        $userInfo = new UserInfo('username', 'password');
        $authority = new Authority('example.com', 8080, $userInfo);

        self::assertSame('example.com', $authority->host);
        self::assertSame(8080, $authority->port);
        self::assertSame($userInfo, $authority->userInfo);
        self::assertSame('username', $authority->user);
        self::assertSame('password', $authority->password);
    }

    public function testConstructorWithHostAndUserInfoWithoutPort(): void
    {
        $userInfo = new UserInfo('username', 'password');
        $authority = new Authority('example.com', null, $userInfo);

        self::assertSame('example.com', $authority->host);
        self::assertNull($authority->port);
        self::assertSame($userInfo, $authority->userInfo);
        self::assertSame('username', $authority->user);
        self::assertSame('password', $authority->password);
    }

    public function testConstructorWithUserInfoWithoutPassword(): void
    {
        $userInfo = new UserInfo('username');
        $authority = new Authority('example.com', 8080, $userInfo);

        self::assertSame('example.com', $authority->host);
        self::assertSame(8080, $authority->port);
        self::assertSame($userInfo, $authority->userInfo);
        self::assertSame('username', $authority->user);
        self::assertNull($authority->password);
    }

    public function testToStringWithHostOnly(): void
    {
        $authority = new Authority('example.com');

        self::assertSame('example.com', (string) $authority);
        self::assertSame('example.com', $authority->toString());
    }

    public function testToStringWithHostAndPort(): void
    {
        $authority = new Authority('example.com', 8080);

        self::assertSame('example.com:8080', (string) $authority);
        self::assertSame('example.com:8080', $authority->toString());
    }

    public function testToStringWithHostPortAndUserInfo(): void
    {
        $userInfo = new UserInfo('username', 'password');
        $authority = new Authority('example.com', 8080, $userInfo);

        self::assertSame('username:password@example.com:8080', (string) $authority);
        self::assertSame('username:password@example.com:8080', $authority->toString());
    }

    public function testToStringWithHostAndUserInfoWithoutPort(): void
    {
        $userInfo = new UserInfo('username', 'password');
        $authority = new Authority('example.com', null, $userInfo);

        self::assertSame('username:password@example.com', (string) $authority);
        self::assertSame('username:password@example.com', $authority->toString());
    }

    public function testToStringWithUserInfoWithoutPassword(): void
    {
        $userInfo = new UserInfo('username');
        $authority = new Authority('example.com', 8080, $userInfo);

        self::assertSame('username@example.com:8080', (string) $authority);
        self::assertSame('username@example.com:8080', $authority->toString());
    }

    public function testEqualsWithSameInstance(): void
    {
        $authority = new Authority('example.com', 8080);

        self::assertTrue($authority->equals($authority));
    }

    public function testEqualsWithIdenticalAuthority(): void
    {
        $authority1 = new Authority('example.com', 8080);
        $authority2 = new Authority('example.com', 8080);

        self::assertTrue($authority1->equals($authority2));
        self::assertTrue($authority2->equals($authority1));
    }

    public function testEqualsWithDifferentHost(): void
    {
        $authority1 = new Authority('example.com', 8080);
        $authority2 = new Authority('example.org', 8080);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testEqualsWithDifferentPort(): void
    {
        $authority1 = new Authority('example.com', 8080);
        $authority2 = new Authority('example.com', 9090);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testEqualsWithDifferentPortNullVsNotNull(): void
    {
        $authority1 = new Authority('example.com', null);
        $authority2 = new Authority('example.com', 8080);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testEqualsWithDifferentUserInfo(): void
    {
        $userInfo1 = new UserInfo('user1', 'pass1');
        $userInfo2 = new UserInfo('user2', 'pass2');
        $authority1 = new Authority('example.com', 8080, $userInfo1);
        $authority2 = new Authority('example.com', 8080, $userInfo2);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testEqualsWithUserInfoVsNoUserInfo(): void
    {
        $userInfo = new UserInfo('username', 'password');
        $authority1 = new Authority('example.com', 8080, $userInfo);
        $authority2 = new Authority('example.com', 8080);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testEqualsWithIdenticalUserInfo(): void
    {
        $userInfo1 = new UserInfo('username', 'password');
        $userInfo2 = new UserInfo('username', 'password');
        $authority1 = new Authority('example.com', 8080, $userInfo1);
        $authority2 = new Authority('example.com', 8080, $userInfo2);

        self::assertTrue($authority1->equals($authority2));
        self::assertTrue($authority2->equals($authority1));
    }

    public function testEqualsWithDifferentTypes(): void
    {
        $authority = new Authority('example.com', 8080);
        $other = 'not an authority';

        self::assertFalse($authority->equals($other));
    }

    public function testEqualsWithNull(): void
    {
        $authority = new Authority('example.com', 8080);

        self::assertFalse($authority->equals(null));
    }

    public function testEqualsWithUserInfoHavingDifferentUser(): void
    {
        $userInfo1 = new UserInfo('user1', 'password');
        $userInfo2 = new UserInfo('user2', 'password');
        $authority1 = new Authority('example.com', 8080, $userInfo1);
        $authority2 = new Authority('example.com', 8080, $userInfo2);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testEqualsWithUserInfoHavingDifferentPassword(): void
    {
        $userInfo1 = new UserInfo('username', 'pass1');
        $userInfo2 = new UserInfo('username', 'pass2');
        $authority1 = new Authority('example.com', 8080, $userInfo1);
        $authority2 = new Authority('example.com', 8080, $userInfo2);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testEqualsWithUserInfoHavingPasswordVsNoPassword(): void
    {
        $userInfo1 = new UserInfo('username', 'password');
        $userInfo2 = new UserInfo('username');
        $authority1 = new Authority('example.com', 8080, $userInfo1);
        $authority2 = new Authority('example.com', 8080, $userInfo2);

        self::assertFalse($authority1->equals($authority2));
        self::assertFalse($authority2->equals($authority1));
    }

    public function testImplementsAuthorityInterface(): void
    {
        $authority = new Authority('example.com');

        self::assertInstanceOf(AuthorityInterface::class, $authority);
    }

    public function testPropertiesAreReadonly(): void
    {
        $authority = new Authority('example.com', 8080);

        self::assertSame('example.com', $authority->host);
        self::assertSame(8080, $authority->port);
        self::assertNull($authority->userInfo);
    }

    public function testUserPropertyDelegatesToUserInfo(): void
    {
        $userInfo = new UserInfo('testuser', 'testpass');
        $authority = new Authority('example.com', 8080, $userInfo);

        self::assertSame('testuser', $authority->user);
    }

    public function testPasswordPropertyDelegatesToUserInfo(): void
    {
        $userInfo = new UserInfo('testuser', 'testpass');
        $authority = new Authority('example.com', 8080, $userInfo);

        self::assertSame('testpass', $authority->password);
    }

    public function testUserPropertyReturnsNullWhenNoUserInfo(): void
    {
        $authority = new Authority('example.com', 8080);

        self::assertNull($authority->user);
    }

    public function testPasswordPropertyReturnsNullWhenNoUserInfo(): void
    {
        $authority = new Authority('example.com', 8080);

        self::assertNull($authority->password);
    }

    public function testPasswordPropertyReturnsNullWhenUserInfoHasNoPassword(): void
    {
        $userInfo = new UserInfo('testuser');
        $authority = new Authority('example.com', 8080, $userInfo);

        self::assertNull($authority->password);
    }

    public function testEdgeCasePortZero(): void
    {
        $authority = new Authority('example.com', 0);

        self::assertSame(0, $authority->port);
        self::assertSame('example.com:0', (string) $authority);
    }

    public function testEdgeCasePortMaxValue(): void
    {
        $authority = new Authority('example.com', 65535);

        self::assertSame(65535, $authority->port);
        self::assertSame('example.com:65535', (string) $authority);
    }

    public function testEdgeCaseEmptyHost(): void
    {
        $authority = new Authority('');

        self::assertSame('', $authority->host);
        self::assertSame('', (string) $authority);
    }

    public function testEdgeCaseHostWithSpecialCharacters(): void
    {
        $authority = new Authority('example-test.com');

        self::assertSame('example-test.com', $authority->host);
        self::assertSame('example-test.com', (string) $authority);
    }

    public function testEdgeCaseHostWithUnderscores(): void
    {
        $authority = new Authority('example_test.com');

        self::assertSame('example_test.com', $authority->host);
        self::assertSame('example_test.com', (string) $authority);
    }
}
