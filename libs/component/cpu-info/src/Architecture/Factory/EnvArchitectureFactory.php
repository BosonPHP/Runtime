<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\Architecture\Factory;

use Boson\Component\CpuInfo\Architecture;
use Boson\Component\CpuInfo\ArchitectureInterface;

final readonly class EnvArchitectureFactory implements ArchitectureFactoryInterface
{
    /**
     * @var non-empty-string
     */
    public const string DEFAULT_OVERRIDE_ENV_NAME = 'BOSON_CPU_ARCH';

    /**
     * @var non-empty-string
     */
    public const string DEFAULT_ENV_NAME = 'PROCESSOR_ARCHITECTURE';

    public function __construct(
        private ArchitectureFactoryInterface $delegate,
        /**
         * @var list<non-empty-string>
         */
        private array $envVariableNames = [
            self::DEFAULT_OVERRIDE_ENV_NAME,
            self::DEFAULT_ENV_NAME,
        ],
    ) {}

    /**
     * @return non-empty-string|null
     */
    private function tryGetNameFromEnvironment(): ?string
    {
        foreach ($this->envVariableNames as $name) {
            $server = $_SERVER[$name] ?? null;

            if (\is_string($server) && $server !== '') {
                return $server;
            }
        }

        return null;
    }

    public function createArchitecture(): ArchitectureInterface
    {
        $name = $this->tryGetNameFromEnvironment();

        if ($name === null) {
            return $this->delegate->createArchitecture();
        }

        return Architecture::from($name);
    }
}
