<?php

declare(strict_types=1);

namespace Boson\Contracts\Id;

/**
 * @template-covariant T of IdInterface =  IdInterface
 */
interface IdentifiableInterface
{
    /**
     * Provides an identifier of an entity
     */
    public IdInterface $id {
        /**
         * @return T
         */
        get;
    }
}
