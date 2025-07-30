<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\Method;

use Boson\Contracts\Http\Component\MethodInterface;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Component\Http\Component\Method
 */
final readonly class HttpMethod implements MethodInterface
{
    use MethodImpl;
}
