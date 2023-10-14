<?php

namespace Core\Providers;

use BadMethodCallException;
use Core\Helper\Helper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;

/**
 * @method void prependMiddlewareToGroup(string $middleware, string $group)
 * @method void pushMiddlewareToGroup(string $middleware, string $group)
 * @method void aliasMiddleware(string $name, string $middleware)
 */
abstract class ServiceProvider extends SupportServiceProvider
{
    /**
     * Service provider classes to register.
     *
     * @var string[]
     */
    protected $providers = [];

    /**
     * The console kernel class of the service provider.
     *
     * @var string
     */
    protected $consoleKernel;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->registerConsoleKernel();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();
    }

    /**
     * Register all specified service providers.
     *
     * @return void
     */
    protected function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Register service's console kernel.
     *
     * @return void
     */
    protected function registerConsoleKernel()
    {
        if (!$this->consoleKernel) {
            return;
        }

        if (!$this->app->runningInConsole()) {
            return;
        }

        $consoleKernel = $this->consoleKernel;

        (new $consoleKernel($this->app, $this->app[Dispatcher::class]))
            ->bootstrap();
    }

    /**
     * Recursively and distinctively merge into the existing auth configuration
     *
     * The merged config will take precedence over existing config.
     *
     * @param string $path
     * @param string $key
     * @return void
     */
    protected function mergeConfigFromReverse($path, $key)
    {
        if (!($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');

            $defaultConfig = $config->get($key, []);
            $customConfig = require $path;

            $config->set($key, Helper::arrayMergeRecursiveDistinct(
                $defaultConfig,
                $customConfig
            ));
        }
    }

    /**
     * Hanlde dynamic method calls.
     *
     * @param string $method
     * @param array $args
     * @return void
     */
    public function __call($method, $args)
    {
        $kernelCallables = [
            'pushMiddleware',
            'prependMiddleware',
            'prependToMiddlewarePriority',
            'appendToMiddlewarePriority',
        ];

        $routerCallables = [
            'prependMiddlewareToGroup',
            'pushMiddlewareToGroup',
            'aliasMiddleware',
        ];

        if (in_array($method, $kernelCallables))
            return $this->app[Kernel::class]->{$method}(...$args);

        elseif (in_array($method, $routerCallables))
            return $this->app[Router::class]->{$method}(...$args);
        else
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method
            ));
    }
}
