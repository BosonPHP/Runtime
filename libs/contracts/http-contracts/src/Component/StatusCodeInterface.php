<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Component;

use Boson\Contracts\Http\Component\StatusCode\StatusCodeCategoryInterface;
use Boson\Contracts\ValueObject\IntValueObjectInterface;
use Boson\Contracts\ValueObject\StringValueObjectInterface;

/**
 * @template T of int = int
 *
 * @template-extends IntValueObjectInterface<T>
 */
interface StatusCodeInterface extends
    IntValueObjectInterface,
    StringValueObjectInterface
{
    /**
     * Gets status code.
     *
     * @var T
     */
    public int $code { get; }

    /**
     * Gets reason phrase message of this status code.
     */
    public string $reason { get; }

    /**
     * Gets category of this status code.
     *
     * Property may contain {@see null} in case of status code is
     * non-standard and category is not known.
     */
    public ?StatusCodeCategoryInterface $category { get; }
}
