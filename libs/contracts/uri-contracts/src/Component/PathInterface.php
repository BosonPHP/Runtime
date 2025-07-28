<?php

declare(strict_types=1);

namespace Boson\Contracts\Uri\Component;

use Boson\Contracts\Uri\UriInterface;

/**
 * Represents the path component of an {@see UriInterface}.
 *
 * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.3
 *
 * @template-extends \Traversable<array-key, non-empty-string>
 */
interface PathInterface extends
    UriComponentInterface,
    \Traversable,
    \Countable
{
    /**
     * @return int<0, max>
     */
    public function count(): int;
}
