<?php

declare(strict_types=1);

namespace Boson\Bridge\Spiral\Command;

use Boson\Application;
use Boson\Bridge\Spiral\BosonScope;
use Spiral\Core\Container;
use Spiral\Core\Scope;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand('boson:start')]
final class StartCommand extends \Spiral\Console\Command
{
    public function __invoke(Container $core): int
    {
        // Set time limit to unlimited
        \set_time_limit(0);

        $core->runScope(
            new Scope(
                name: BosonScope::Boson,
            ),
            static function (Application $app): void {
                $app->run();
            },
        );

        return Command::SUCCESS;
    }
}
