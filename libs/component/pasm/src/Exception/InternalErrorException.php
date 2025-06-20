<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Exception;

class InternalErrorException extends PasmException
{
    public static function becauseInternalErrorOccurs(string $message, ?\Throwable $prev = null): self
    {
        return new self($message, previous: $prev);
    }
}
