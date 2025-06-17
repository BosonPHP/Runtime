<?php

declare(strict_types=1);

namespace Boson\Component\Compiler\Command;

use Boson\Component\Compiler\Command\PackCommand\PackApplicationWorkflowPresenter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PackCommand extends ConfigAwareCommand
{
    public function __construct(?string $name = null)
    {
        parent::__construct($name ?? 'pack');
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Pack application files to PHAR assembly');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->getConfiguration($input);

        $presenter = new PackApplicationWorkflowPresenter();

        try {
            $presenter->process($config, $output);
        } catch (\Throwable $e) {
            return $this->fail($output, $e);
        }

        return self::SUCCESS;
    }
}
