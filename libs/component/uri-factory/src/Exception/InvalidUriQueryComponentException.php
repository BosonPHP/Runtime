<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Exception;

class InvalidUriQueryComponentException extends InvalidUriComponentException
{
    public static function becauseStringCastingErrorOccurs(\Stringable $query, \Throwable $e): self
    {
        $message = 'An error occurred while converting an URI query component of type %s to a string';

        return new self(\sprintf($message, $query::class), previous: $e);
    }
}
