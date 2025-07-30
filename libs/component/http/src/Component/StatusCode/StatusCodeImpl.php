<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode;

use Boson\Contracts\Http\Component\StatusCode\StatusCodeCategoryInterface;
use Boson\Contracts\Http\Component\StatusCodeInterface;

/**
 * @phpstan-require-implements StatusCodeInterface
 */
trait StatusCodeImpl
{
    public function __construct(
        public readonly int $code,
        public readonly string $reason = '',
        public readonly ?StatusCodeCategoryInterface $category = null,
    ) {}

    public function toInteger(): int
    {
        return $this->code;
    }

    public function toString(): string
    {
        return $this->reason;
    }

    public function equals(mixed $other): bool
    {
        return $other === $this
            || ($other instanceof self
                && $other->code === $this->code);
    }

    public function __toString(): string
    {
        if ($this->reason !== '') {
            return \sprintf('%d %s', $this->code, $this->reason);
        }

        return (string) $this->code;
    }
}
