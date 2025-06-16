<?php

declare(strict_types=1);

namespace Boson\Bridge\Symfony\Runtime;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Runtime\RunnerInterface;
use Symfony\Component\Runtime\SymfonyRuntime;

/**
 * @api
 */
final class BosonRuntime extends SymfonyRuntime
{
    public function getRunner(?object $application): RunnerInterface
    {
        if ($application instanceof Kernel) {
            return new BosonRunner($application);
        }

        return parent::getRunner($application);
    }
}
