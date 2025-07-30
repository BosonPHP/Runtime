<?php

declare(strict_types=1);

namespace Boson\Contracts\Http\Factory\Component;

use Boson\Contracts\Http\Factory\Exception\InvalidMessageArgumentExceptionInterface;
use Boson\Contracts\Http\Factory\MessageFactoryInterface;
use Boson\Contracts\Http\MessageInterface;

/**
 * @phpstan-import-type BodyInputType from MessageFactoryInterface
 * @phpstan-import-type BodyOutputType from MessageInterface
 */
interface BodyFactoryInterface
{
    /**
     * @param BodyInputType $body
     *
     * @return BodyOutputType
     * @throws InvalidMessageArgumentExceptionInterface in case of
     *         body argument is invalid and body cannot be created
     */
    public function createBodyFromString(string|\Stringable $body): string;
}
