<?php

declare(strict_types=1);

namespace Boson\Component\Pasm\Exception;

class NoAvailableDriverException extends PasmException
{
    public static function becauseNoDriverSupported(?\Throwable $prev = null): self
    {
        return new self('No suitable driver found', previous: $prev);
    }
}
