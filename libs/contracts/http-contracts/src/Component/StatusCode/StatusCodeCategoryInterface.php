<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Component\StatusCode;

use Boson\Contracts\ValueObject\StringValueObjectInterface;

/**
 * @template-extends StringValueObjectInterface<non-empty-string>
 */
interface StatusCodeCategoryInterface extends StringValueObjectInterface
{
    /**
     * @var non-empty-string
     */
    public string $name { get; }
}
