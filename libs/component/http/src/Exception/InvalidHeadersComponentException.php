<?php

declare(strict_types=1);

namespace Boson\Component\Http\Exception;

use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;

class InvalidHeadersComponentException extends \InvalidArgumentException implements
    InvalidMessageArgumentExceptionInterface {}
