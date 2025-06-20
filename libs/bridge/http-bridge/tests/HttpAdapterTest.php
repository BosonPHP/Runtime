<?php

declare(strict_types=1);

namespace Boson\Bridge\Http\Tests;

use Boson\Bridge\Http\Tests\Stub\TestHttpAdapter;
use Boson\Component\GlobalsProvider\ServerGlobalsProviderInterface;
use Boson\Component\Http\Body\BodyDecoderInterface;
use Boson\Component\Http\Request;
use Boson\Contracts\Http\RequestInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/http-bridge')]
final class HttpAdapterTest extends TestCase
{
    public function testConstructorUsesDefaults(): void
    {
        $adapter = $this->createTestAdapter();

        self::assertInstanceOf(ServerGlobalsProviderInterface::class, $this->getProperty($adapter, 'server'));
        self::assertInstanceOf(BodyDecoderInterface::class, $this->getProperty($adapter, 'post'));
    }

    public function testConstructorUsesCustomDependencies(): void
    {
        $server = $this->createMock(ServerGlobalsProviderInterface::class);
        $body = $this->createMock(BodyDecoderInterface::class);
        $adapter = $this->createTestAdapter($server, $body);

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

        $adapter = $this->createTestAdapter(null, $body);

        self::assertSame(['foo' => 'bar'], $adapter->callGetDecodedBody($request));
    }

    public function testGetServerParametersDelegatesToProvider(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $server = $this->createMock(ServerGlobalsProviderInterface::class);
        $server->expects(self::once())
            ->method('getServerGlobals')
            ->with($request)
            ->willReturn(['X-FOO' => 'bar']);

        $adapter = $this->createTestAdapter($server);

        self::assertSame(['X-FOO' => 'bar'], $adapter->callGetServerParameters($request));
    }

    public function testGetQueryParametersParsesQueryString(): void
    {
        $request = new Request(url: 'https://example.com/foo?bar=baz&arr[]=1&arr[]=2');
        $adapter = $this->createTestAdapter();

        $result = $adapter->callGetQueryParameters($request);

        self::assertSame(['bar' => 'baz', 'arr' => ['1', '2']], $result);
    }

    public function testGetQueryParametersReturnsEmptyArrayForEmptyQuery(): void
    {
        $request = new Request(url: 'https://example.com/foo');
        $adapter = $this->createTestAdapter();

        $result = $adapter->callGetQueryParameters($request);

        self::assertSame([], $result);
    }

    private function createTestAdapter(
        ?ServerGlobalsProviderInterface $server = null,
        ?BodyDecoderInterface $body = null
    ): TestHttpAdapter {
        return new TestHttpAdapter($server, $body);
    }

    private function getProperty(object $object, string $property): mixed
    {
        return new \ReflectionProperty($object, $property)
            ->getValue($object);
    }
}
