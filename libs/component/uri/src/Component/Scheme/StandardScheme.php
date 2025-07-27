<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Component\Scheme;

use Boson\Contracts\Uri\Component\SchemeInterface;

/**
 * An implementation of the standard HTTP schemes.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Boson\Component\Uri\Component
 */
final readonly class StandardScheme implements SchemeInterface
{
    use SchemeImpl {
        SchemeImpl::__construct as private __schemeImplConstruct;
    }

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        /**
         * Gets default (known) port of the standard scheme (if available).
         *
         * @var int<0, 65535>|null
         */
        public ?int $port = null,
    ) {
        $this->__schemeImplConstruct($name);
    }
}
