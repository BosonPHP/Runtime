<?php

declare(strict_types=1);

namespace Boson\Component\Http\Exception;

use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;

class InvalidBodyComponentException extends \InvalidArgumentException implements
    InvalidMessageArgumentExceptionInterface
{
    public static function becauseStringCastingErrorOccurs(\Stringable $body, \Throwable $e): self
    {
        $message = 'An error occurred while converting the HTTP body component of type %s to a string';

        return new self(\sprintf($message, $body::class), previous: $e);
    }
}
