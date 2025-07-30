<?php

declare(strict_types=1);

namespace Boson\Component\Http\Exception;

class InvalidHeaderValueException extends InvalidHeadersComponentException
{
    public static function becauseHeaderValueIsNotStringOrArray(mixed $value, ?\Throwable $previous = null): self
    {
        $message = \sprintf('Header value must be a string or list<string>, but "%s" given', \get_debug_type($value));

        return new self($message, previous: $previous);
    }

    public static function becauseHeaderValueIsNotString(mixed $value, ?\Throwable $previous = null): self
    {
        $message = \sprintf('Each header value must be a string, but "%s" given', \get_debug_type($value));

        return new self($message, previous: $previous);
    }
}
