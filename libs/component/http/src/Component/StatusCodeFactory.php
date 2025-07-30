<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component;

use Boson\Contracts\Http\Component\StatusCodeInterface;
use Boson\Contracts\Http\Factory\Component\StatusCodeFactoryInterface;

final readonly class StatusCodeFactory implements StatusCodeFactoryInterface
{
    public function createStatusCode(int $code, ?string $reason = null): StatusCodeInterface
    {
        return StatusCode::tryFrom($code)
            ?? new StatusCode($code, (string) $reason);
    }
}
