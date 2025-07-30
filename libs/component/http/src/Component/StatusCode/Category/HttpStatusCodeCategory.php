<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode\Category;

use Boson\Contracts\Http\Component\StatusCode\StatusCodeCategoryInterface;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Component\Http\Component\StatusCode\Category
 */
final readonly class HttpStatusCodeCategory implements StatusCodeCategoryInterface
{
    use StatusCodeCategoryImpl;
}
