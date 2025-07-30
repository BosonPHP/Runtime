<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode\Category;

use Boson\Contracts\Http\Component\StatusCode\StatusCodeCategoryInterface;

/**
 * @phpstan-require-implements StatusCodeCategoryInterface
 */
trait StatusCodeCategoryImpl
{
    public function __construct(
        /**
         * @var non-empty-string
         */
        public readonly string $name,
    ) {}

    public function equals(mixed $other): bool
    {
        return $other === $this
            || ($other instanceof self
                && $other->name === $this->name);
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
