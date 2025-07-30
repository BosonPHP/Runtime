<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Component;

use Boson\Contracts\ValueObject\StringValueObjectInterface;

/**
 * HTTP defines a set of request methods to indicate the purpose of the
 * request and what is expected if the request is successful.
 *
 * Although they can also be nouns, these request methods are sometimes
 * referred to as HTTP verbs. Each request method has its own semantics,
 * but some characteristics are shared across multiple methods,
 * specifically request methods can be safe, idempotent, or cacheable.
 *
 * @template-extends StringValueObjectInterface<non-empty-uppercase-string>
 */
interface MethodInterface extends StringValueObjectInterface
{
    /**
     * The name of the HTTP method (e.g., `GET`, `POST`, `PUT`, `DELETE`).
     * This is a human-readable string representation of the method.
     *
     * Method name MUST be uppercased.
     *
     * @var non-empty-uppercase-string
     */
    public string $name { get; }

    /**
     * An HTTP method is idempotent if the intended effect on the server of
     * making a single request is the same as the effect of making several
     * identical requests.
     *
     * Property may contain {@see null} in case of method is non-standard
     * and behaviour is not known.
     */
    public ?bool $isIdempotent { get; }

    /**
     * An HTTP method is safe if it doesn't alter the state of the server.
     * In other words, a method is safe if it leads to a read-only operation.
     *
     * Property may contain {@see null} in case of method is non-standard
     * and behaviour is not known.
     */
    public ?bool $isSafe { get; }
}
