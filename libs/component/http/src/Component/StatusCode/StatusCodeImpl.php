<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode;

use Boson\Contracts\Http\Component\StatusCode\StatusCodeCategoryInterface;
use Boson\Contracts\Http\Component\StatusCodeInterface;

/**
 * @template T of int = int
 *
 * @phpstan-require-implements StatusCodeInterface
 */
trait StatusCodeImpl
{
    /**
     * @var T
     */
    public readonly int $code;

    public readonly string $reason;

    public readonly ?StatusCodeCategoryInterface $category;

    /**
     * @param T $code
     */
    public function __construct(
        int $code,
        string $reason = '',
        ?StatusCodeCategoryInterface $category = null,
    ) {
        /** @phpstan-ignore-next-line : PHPStan false-positive (assigned outside of constructor) */
        $this->code = $code;
        /** @phpstan-ignore-next-line : PHPStan false-positive (assigned outside of constructor) */
        $this->reason = $reason;
        /** @phpstan-ignore-next-line : PHPStan false-positive (assigned outside of constructor) */
        $this->category = $category;
    }

    /**
     * @return T
     */
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
