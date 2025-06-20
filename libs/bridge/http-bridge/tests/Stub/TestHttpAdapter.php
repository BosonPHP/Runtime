<?php

declare(strict_types=1);

namespace Boson\Bridge\Http\Tests\Stub;

use Boson\Bridge\Http\HttpAdapter;
use Boson\Component\Http\Response;
use Boson\Contracts\Http\RequestInterface;
use Boson\Contracts\Http\ResponseInterface;

final readonly class TestHttpAdapter extends HttpAdapter
{
    public function createRequest(RequestInterface $request): object
    {
        return (object) [];
    }

    public function createResponse(object $response): ResponseInterface
    {
        return new Response();
    }

    public function callGetDecodedBody(RequestInterface $request): array
    {
        return $this->getDecodedBody($request);
    }

    public function callGetServerParameters(RequestInterface $request): array
    {
        return $this->getServerParameters($request);
    }

    public function callGetQueryParameters(RequestInterface $request): array
    {
        return $this->getQueryParameters($request);
    }
}
