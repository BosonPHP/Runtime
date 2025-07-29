<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Exception;

class InvalidUriSchemeComponentException extends InvalidUriComponentException
{
    public static function becauseUriSchemeComponentIsEmpty(?\Throwable $previous = null): self
    {
        return new self('URI scheme cannot be empty', previous: $previous);
    }
}
