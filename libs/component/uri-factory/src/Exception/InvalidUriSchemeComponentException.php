<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Exception;

class InvalidUriSchemeComponentException extends InvalidUriComponentException
{
    public static function becauseStringCastingErrorOccurs(\Stringable $scheme, \Throwable $e): self
    {
        $message = 'An error occurred while converting an URI scheme component of type %s to a string';

        return new self(\sprintf($message, $scheme::class), previous: $e);
    }

    public static function becauseUriSchemeComponentIsEmpty(?\Throwable $previous = null): self
    {
        return new self('URI scheme cannot be empty', previous: $previous);
    }
}
