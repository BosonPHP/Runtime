<?php

declare(strict_types=1);

namespace Boson\Bridge\Laravel\Provider;

use Boson\Application as Boson;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\View\Engines\EngineResolver;
use Laravel\Octane\ApplicationFactory;
use Laravel\Octane\ApplicationGateway;
use Laravel\Octane\Contracts\Worker as WorkerContract;
use Laravel\Octane\CurrentApplication;
use Laravel\Octane\DispatchesEvents;
use Laravel\Octane\Events\WorkerErrorOccurred;
use Laravel\Octane\Events\WorkerStarting;
use Laravel\Octane\Events\WorkerStopping;
use Laravel\Octane\RequestContext;
use Symfony\Component\HttpFoundation\Response;

class Worker implements WorkerContract
{
    use DispatchesEvents;

    private Application $app;

    private ?Response $response = null;

    public function __construct(
        private readonly ApplicationFactory $appFactory,
        private readonly Boson $boson,
    ) {}

    /**
     * @param array<string, mixed> $initialInstances
     */
    public function boot(array $initialInstances = []): void
    {
        $this->app = $app = $this->appFactory->createApplication($initialInstances);

        $this->dispatchEvent($app, new WorkerStarting($app));
    }

    public function handle(Request $request, RequestContext $context): void
    {
        $sandbox = clone $this->app;

        CurrentApplication::set($sandbox);

        $sandbox->scoped(Boson::class, fn() => $this->boson);

        $gateway = new ApplicationGateway($this->app, $sandbox);

        try {
            $this->response = $gateway->handle($request);

            $gateway->terminate($request, $this->response);
        } catch (\Throwable $e) {
            $this->dispatchEvent($sandbox, new WorkerErrorOccurred($e, $sandbox));
        } finally {
            $sandbox->flush();

            $resolver = $this->app->make('view.engine.resolver');

            if ($resolver instanceof EngineResolver) {
                $resolver->forget('blade');
                $resolver->forget('php');
            }

            unset($gateway, $sandbox);

            CurrentApplication::set($this->app);
        }
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function handleTask($data): void {}

    public function terminate(): void
    {
        $this->dispatchEvent($this->app, new WorkerStopping($this->app));
    }
}
