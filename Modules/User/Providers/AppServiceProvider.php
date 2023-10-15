<?php

namespace Modules\User\Providers;

use App\Models\User;
use Core\Providers\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\User\Services\UserService;
use Modules\User\Contracts\UserRepository;
use Modules\User\Contracts\UserService as UserServiceContract;

use Modules\User\Repositories\UserRepositoryEloquent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    protected $providers = [
        UserServiceProvider::class,
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
            app(User::class)->getTable() => User::class,
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
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(UserServiceContract::class, UserService::class);

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
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', UserService::name());
    }
}
