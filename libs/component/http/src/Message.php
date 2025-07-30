<?php

declare(strict_types=1);

namespace Boson\Component\Http;

use Boson\Contracts\Http\MessageInterface;

class Message implements MessageInterface
{
    public function __construct(
        iterable $headers,
        string|\Stringable $body,
    ) {}
}
