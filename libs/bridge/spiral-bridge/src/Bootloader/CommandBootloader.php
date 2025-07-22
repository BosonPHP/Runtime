<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Bootloader;

use Boson\Bridge\Spiral\Command\StartCommand;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\Core\Container;

final class CommandBootloader extends Bootloader
{
    public function init(ConsoleBootloader $console, Container $container): void
    {
        $console->addCommand(StartCommand::class);
    }
}
