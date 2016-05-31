<?php

namespace Pipes\Providers;

use Pipes\Pipeline\Execution\Executors\SSHExecutor;
use Pipes\Pipeline\Execution\Manager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('ExecutorManager', function ($app) {
            return $app->make(Manager::class);
        });

        $manager = $this->app->make('ExecutorManager');
        $manager->register(new SSHExecutor());
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}
