<?php

declare(strict_types=1);

namespace Boson\Bridge\Symfony;

use Boson\Bridge\Symfony\DependencyInjection\BosonExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BosonBundle extends Bundle
{
    public function getContainerExtension(): BosonExtension
    {
        return new BosonExtension();
    }


}
