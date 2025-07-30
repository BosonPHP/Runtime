<?php

declare(strict_types=1);

namespace Boson\Component\Http\Exception;

use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;
use Boson\Contracts\Uri\Factory\Exception\InvalidUriExceptionInterface;

class InvalidUrlComponentException extends \InvalidArgumentException implements
    InvalidMessageArgumentExceptionInterface
{
    public static function becauseUriIsInvalid(InvalidUriExceptionInterface $e): self
    {
        return new self($e->getMessage(), previous: $e);
    }
}
