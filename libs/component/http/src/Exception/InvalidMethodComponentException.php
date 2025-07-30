<?php

declare(strict_types=1);

namespace Boson\Component\Http\Exception;

use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;

class InvalidMethodComponentException extends \InvalidArgumentException implements
    InvalidMessageArgumentExceptionInterface
{
    public static function becauseStringCastingErrorOccurs(\Stringable $method, \Throwable $e): self
    {
        $message = 'An error occurred while converting the HTTP method component of type %s to a string';

        return new self(\sprintf($message, $method::class), previous: $e);
    }

    public static function becauseMethodIsEmpty(?\Throwable $previous = null): self
    {
        return new self('HTTP method cannot be empty', previous: $previous);
    }
}
