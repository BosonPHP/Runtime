<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Exception;

class InvalidUriPathComponentException extends InvalidUriComponentException
{
    public static function becauseStringCastingErrorOccurs(\Stringable $path, \Throwable $e): self
    {
        $message = 'An error occurred while converting an URI path component of type %s to a string';

        return new self(\sprintf($message, $path::class), previous: $e);
    }
}
