<?php

namespace Modules\Authentication\Providers;

use Core\Providers\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\Authentication\Services\AuthenticationService;
use Modules\Authentication\Contracts\AuthenticationRepository;
use Modules\Authentication\Contracts\AuthenticationService as AuthenticationServiceContract;
use Modules\Authentication\Models\Authentication;
use Modules\Authentication\Repositories\AuthenticationRepositoryEloquent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    protected $providers = [
        AuthenticationServiceProvider::class,
        RouteServiceProvider::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMigrations();

        Relation::morphMap([
            app(Authentication::class)->getTable() => Authentication::class,
        ]);

        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->app->bind(AuthenticationRepository::class, AuthenticationRepositoryEloquent::class);
        $this->app->bind(AuthenticationServiceContract::class, AuthenticationService::class);

        parent::register();
    }

    /**
     * Register migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $serviceMigrationPath = __DIR__ . '/../Database/Migrations';

        $this->loadMigrationsFrom($serviceMigrationPath);

        $this->publishes([
            $serviceMigrationPath => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', AuthenticationService::name());
    }
}
