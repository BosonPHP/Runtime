<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Exception;

use Boson\Contracts\Uri\Factory\Exception\InvalidUriComponentExceptionInterface;
use Boson\Contracts\Uri\Factory\Exception\InvalidUriExceptionInterface;

class InvalidUriException extends \InvalidArgumentException implements
    InvalidUriExceptionInterface
{
    public static function becauseStringCastingErrorOccurs(\Stringable $uri, \Throwable $e): self
    {
        $message = 'An error occurred while converting an URI of type %s to a string';

        return new self(\sprintf($message, $uri::class), previous: $e);
    }

    public static function becauseUriComponentIsInvalid(InvalidUriComponentExceptionInterface $e): self
    {
        return new self($e->getMessage(), previous: $e);
    }
}
