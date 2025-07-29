<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Tests;

use Boson\Contracts\Http\Body\BodyProviderInterface;
use Boson\Contracts\Http\Body\MutableBodyProviderInterface;
use Boson\Contracts\Http\Headers\HeadersProviderInterface;
use Boson\Contracts\Http\Headers\MutableHeadersProviderInterface;
use Boson\Contracts\Http\HeadersInterface;
use Boson\Contracts\Http\Method\MethodProviderInterface;
use Boson\Contracts\Http\Method\MutableMethodProviderInterface;
use Boson\Contracts\Http\MutableHeadersInterface;
use Boson\Contracts\Http\MutableRequestInterface;
use Boson\Contracts\Http\MutableResponseInterface;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Http\ResponseInterface;
use Boson\Contracts\Http\StatusCode\MutableStatusCodeProviderInterface;
use Boson\Contracts\Http\StatusCode\StatusCodeProviderInterface;
use Boson\Contracts\Http\Url\MutableUrlProviderInterface;
use Boson\Contracts\Http\Url\UrlProviderInterface;
use Boson\Contracts\Uri\UriInterface;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('boson-php/http-contracts')]
final class CompatibilityTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testBodyProviderCompatibility(): void
    {
        new class implements BodyProviderInterface {
            public string $body {
                get => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableBodyProviderCompatibility(): void
    {
        new class implements MutableBodyProviderInterface {
            public string $body {
                get => $value;
                set(string|\Stringable $value) => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testHeadersProviderCompatibility(): void
    {
        new class implements HeadersProviderInterface {
            public HeadersInterface $headers {
                get => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableHeadersProviderCompatibility(): void
    {
        new class implements MutableHeadersProviderInterface {
            public MutableHeadersInterface $headers {
                get => $value;
                set(iterable $headers) => $headers;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMethodProviderCompatibility(): void
    {
        new class implements MethodProviderInterface {
            public string $method {
                get => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableMethodProviderCompatibility(): void
    {
        new class implements MutableMethodProviderInterface {
            public string $method {
                get => $value;
                set(string|\Stringable $method) => $method;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testStatusCodeProviderCompatibility(): void
    {
        new class implements StatusCodeProviderInterface {
            public int $status {
                get => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableStatusCodeProviderCompatibility(): void
    {
        new class implements MutableStatusCodeProviderInterface {
            public int $status {
                get => $value;
                set(int $status) => $status;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testUrlProviderCompatibility(): void
    {
        new class implements UrlProviderInterface {
            public UriInterface $url {
                get => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableUrlProviderCompatibility(): void
    {
        new class implements MutableUrlProviderInterface {
            public UriInterface $url {
                get => $value;
                set(string|\Stringable $url) => $url;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testHeadersCompatibility(): void
    {
        new class implements HeadersInterface, \IteratorAggregate {
            public function first(string $name, ?string $default = null): ?string {}

            public function all(string $name): array {}

            public function has(string $name): bool {}

            public function contains(string $name, string $value): bool {}

            public function count(): int {}

            public function getIterator(): \Traversable {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableHeadersCompatibility(): void
    {
        new class implements MutableHeadersInterface, \IteratorAggregate {
            public function first(string $name, ?string $default = null): ?string {}

            public function all(string $name): array {}

            public function has(string $name): bool {}

            public function contains(string $name, string $value): bool {}

            public function count(): int {}

            public function getIterator(): \Traversable {}

            public function set(string $name, string $value): void {}

            public function add(string $name, string $value): void {}

            public function remove(string $name): void {}

            public function removeAll(): void {}
        };
    }

    #[DoesNotPerformAssertions]
    public function testRequestCompatibility(): void
    {
        new class implements RequestInterface {
            public string $body {
                get => $value;
            }

            public HeadersInterface $headers {
                get => $value;
            }

            public string $method {
                get => $value;
            }

            public UriInterface $url {
                get => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableRequestCompatibility(): void
    {
        new class implements MutableRequestInterface {
            public string $body {
                get => $value;
                set(string|\Stringable $value) => $value;
            }

            public MutableHeadersInterface $headers {
                get => $value;
                set(iterable $headers) => $headers;
            }

            public string $method {
                get => $value;
                set(string|\Stringable $method) => $method;
            }

            public UriInterface $url {
                get => $value;
                set(string|\Stringable $url) => $url;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testResponseCompatibility(): void
    {
        new class implements ResponseInterface {
            public string $body {
                get => $value;
            }

            public HeadersInterface $headers {
                get => $value;
            }

            public int $status {
                get => $value;
            }
        };
    }

    #[DoesNotPerformAssertions]
    public function testMutableResponseCompatibility(): void
    {
        new class implements MutableResponseInterface {
            public string $body {
                get => $value;
                set(string|\Stringable $value) => $value;
            }

            public MutableHeadersInterface $headers {
                get => $value;
                set(iterable $headers) => $headers;
            }

            public int $status {
                get => $value;
                set(int $status) => $status;
            }
        };
    }
}
