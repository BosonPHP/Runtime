<?php

declare(strict_types=1);

namespace Boson\Contracts\Http;

use Boson\Contracts\Http\Component\StatusCodeInterface;

/**
 * @phpstan-type StatusCodeOutputType StatusCodeInterface
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Gets status code integer value of HTTP response.
     *
     * @var StatusCodeOutputType
     */
    public StatusCodeInterface $status {
        get;
    }
}
