<?php

declare(strict_types=1);

namespace Boson\Component\Http\Exception;

class InvalidHeaderNameException extends InvalidHeadersComponentException
{
    public static function becauseHeaderNameIsEmpty(int $index, ?\Throwable $previous = null): self
    {
        $message = \sprintf('Header name #%d cannot be empty', $index);

        return new self($message, previous: $previous);
    }

    public static function becauseHeaderNameIsNotString(mixed $name, ?\Throwable $previous = null): self
    {
        $message = \sprintf('Header name must be non-empty string, but "%s" given', \get_debug_type($name));

        return new self($message, previous: $previous);
    }
}
