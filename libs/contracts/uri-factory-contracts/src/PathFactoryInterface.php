<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Factory;

use Boson\Contracts\Uri\Component\PathInterface;

interface PathFactoryInterface
{
    /**
     * Returns {@see PathInterface} from {@see string} path representation.
     */
    public function createPathFromString(string $path): PathInterface;
}
