<?php

declare(strict_types=1);

namespace Boson\Bridge\Http\Tests;

use Boson\Bridge\Http\HttpAdapter;
use Boson\Component\GlobalsProvider\ServerGlobalsProviderInterface;
use Boson\Component\Http\Body\BodyDecoderInterface;
use Boson\Component\Http\Request;
use Boson\Component\Http\Response;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Http\ResponseInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/http-bridge')]
final class HttpAdapterTest extends TestCase
{
    public function testConstructorUsesDefaults(): void
    {
        $adapter = new readonly class extends HttpAdapter {
            public function createRequest(RequestInterface $request): object
            {
                return (object)[];
            }

            public function createResponse(object $response): ResponseInterface
            {
                return new Response();
            }
        };

        self::assertInstanceOf(ServerGlobalsProviderInterface::class, $this->getProperty($adapter, 'server'));
        self::assertInstanceOf(BodyDecoderInterface::class, $this->getProperty($adapter, 'post'));
    }

    private function getProperty(object $object, string $property): mixed
    {
        return new \ReflectionProperty($object, $property)
            ->getValue($object);
    }

    public function testConstructorUsesCustomDependencies(): void
    {
        $server = $this->createMock(ServerGlobalsProviderInterface::class);
        $body = $this->createMock(BodyDecoderInterface::class);

        $adapter = new readonly class($server, $body) extends HttpAdapter {
            public function createRequest(RequestInterface $request): object
            {
                return (object)[];
            }

            public function createResponse(object $response): ResponseInterface
            {
                return new Response();
            }
        };

        self::assertSame($server, $this->getProperty($adapter, 'server'));
        self::assertSame($body, $this->getProperty($adapter, 'post'));
    }

    public function testGetDecodedBodyDelegatesToBodyDecoder(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $body = $this->createMock(BodyDecoderInterface::class);
        $body->expects(self::once())
            ->method('decode')
            ->with($request)
            ->willReturn(['foo' => 'bar']);

        $adapter = new readonly class(null, $body) extends HttpAdapter {
            public function createRequest(RequestInterface $request): object
            {
                return (object)[];
            }

            public function createResponse(object $response): ResponseInterface
            {
                return new Response();
            }

            public function callGetDecodedBody($request): array
            {
                return $this->getDecodedBody($request);
            }
        };

        self::assertSame(['foo' => 'bar'], $adapter->callGetDecodedBody($request));
    }

    public function testGetServerParametersDelegatesToProvider(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $server = $this->createMock(ServerGlobalsProviderInterface::class);
        $server
            ->expects(self::once())
            ->method('getServerGlobals')
            ->with($request)
            ->willReturn(['X-FOO' => 'bar']);

        $adapter = new readonly class($server) extends HttpAdapter {
            public function createRequest(RequestInterface $request): object
            {
                return (object)[];
            }

            public function createResponse(object $response): ResponseInterface
            {
                return new Response();
            }

            public function callGetServerParameters($request): array
            {
                return $this->getServerParameters($request);
            }
        };

        self::assertSame(['X-FOO' => 'bar'], $adapter->callGetServerParameters($request));
    }

    public function testGetQueryParametersParsesQueryString(): void
    {
        $request = new Request(url: 'https://example.com/foo?bar=baz&arr[]=1&arr[]=2');

        $adapter = new readonly class extends HttpAdapter {
            public function createRequest(RequestInterface $request): object
            {
                return (object)[];
            }

            public function createResponse(object $response): ResponseInterface
            {
                return new Response();
            }

            public function callGetQueryParameters($request): array
            {
                return $this->getQueryParameters($request);
            }
        };

        $result = $adapter->callGetQueryParameters($request);

        self::assertSame(['bar' => 'baz', 'arr' => ['1', '2']], $result);
    }

    public function testGetQueryParametersReturnsEmptyArrayForEmptyQuery(): void
    {
        $request = new Request(url: 'https://example.com/foo');

        $adapter = new readonly class extends HttpAdapter {
            public function createRequest(RequestInterface $request): object
            {
                return (object)[];
            }

            public function createResponse(object $response): ResponseInterface
            {
                return new Response();
            }

            public function callGetQueryParameters($request): array
            {
                return $this->getQueryParameters($request);
            }
        };
        $result = $adapter->callGetQueryParameters($request);
        self::assertSame([], $result);
    }
}
