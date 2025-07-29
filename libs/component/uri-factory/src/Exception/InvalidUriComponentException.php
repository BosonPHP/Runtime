<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Exception;

use Boson\Contracts\Uri\Factory\Exception\InvalidUriComponentExceptionInterface;

class InvalidUriComponentException extends \InvalidArgumentException implements
    InvalidUriComponentExceptionInterface {}
