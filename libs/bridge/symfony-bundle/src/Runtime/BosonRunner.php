<?php

declare(strict_types=1);

namespace Boson\Bridge\Symfony\Runtime;

use Boson\Application;
use Boson\Bridge\Symfony\Http\SymfonyHttpAdapter;
use Boson\Component\Http\Static\StaticProviderInterface;
use Boson\Event\ApplicationStarted;
use Boson\WebView\Api\Schemes\Event\SchemeRequestReceived;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Runtime\RunnerInterface;

final readonly class BosonRunner implements RunnerInterface
{
    public function __construct(
        private Kernel $kernel,
    ) {}

    public function run(): int
    {
        $this->kernel->boot();

        $container = $this->kernel->getContainer();

        /** @var Application $app */
        $app = $container->get(Application::class);

        /** @var SymfonyHttpAdapter $http */
        $http = $container->get(SymfonyHttpAdapter::class);

        /** @var StaticProviderInterface $static */
        $static = $container->get(StaticProviderInterface::class);

        $app->on(function (SchemeRequestReceived $e) use ($http, $static): void {
            $staticBosonResponse = $static->findFileByRequest($e->request);

            if ($staticBosonResponse !== null) {
                $e->response = $staticBosonResponse;
                return;
            }

            $symfonyRequest = $http->createRequest($e->request);
            $symfonyResponse = $this->kernel->handle($symfonyRequest);

            $e->response = $http->createResponse($symfonyResponse);

            if ($this->kernel instanceof TerminableInterface) {
                $this->kernel->terminate($symfonyRequest, $symfonyResponse);
            }
        });

        $app->on(function (ApplicationStarted $e) use ($container): void {
            $entrypoint = $container->getParameter('boson.entrypoint');

            if (\is_string($entrypoint) && $entrypoint !== '') {
                $e->subject->webview->url = $entrypoint;
            }
        });

        $app->run();

        return 0;
    }
}
