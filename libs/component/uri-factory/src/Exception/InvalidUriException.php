<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Exception;

use Boson\Contracts\Uri\Factory\Exception\InvalidUriComponentExceptionInterface;
use Boson\Contracts\Uri\Factory\Exception\InvalidUriExceptionInterface;

class InvalidUriException extends \InvalidArgumentException implements
    InvalidUriExceptionInterface
{
    public static function becauseUriComponentIsInvalid(InvalidUriComponentExceptionInterface $e): self
    {
        return new self($e->getMessage(), previous: $e);
    }
}
