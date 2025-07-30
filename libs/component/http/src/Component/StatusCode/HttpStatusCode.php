<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode;

use Boson\Contracts\Http\Component\StatusCodeInterface;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Component\Http\Component\StatusCode
 *
 * @template-implements StatusCodeInterface<int<100, 599>>
 */
final readonly class HttpStatusCode implements StatusCodeInterface
{
    use StatusCodeImpl {
        StatusCodeImpl::__construct as private __statusCodeImplConstruct;
    }

    /**
     * @param int<100, 599> $code
     * @param non-empty-string $reason
     */
    public function __construct(int $code, string $reason)
    {
        $category = StatusCodeCategory::tryFromHttpStatusCode($code);

        $this->__statusCodeImplConstruct($code, $reason, $category);
    }
}
