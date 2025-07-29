<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory\Component;

use Boson\Contracts\Uri\Component\PathInterface;
use Boson\Contracts\Uri\Factory\Exception\InvalidUriComponentExceptionInterface;

interface UriPathFactoryInterface
{
    /**
     * Returns {@see PathInterface} from {@see string} or {@see \Stringable}
     * path representation.
     *
     * @throws InvalidUriComponentExceptionInterface in case of invalid path
     *         argument is passed.
     */
    public function createPathFromString(string|\Stringable $path): PathInterface;
}
