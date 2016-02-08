<?php

namespace App\Providers;

use App\Pipeline\Execution\Executors\SSHExecutor;
use App\Pipeline\Executors\Manager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ExecutorManager', function ($app) {
            return $app->make(Manager::class);
        });

        $manager = $this->app->make('ExecutorManager');
        $manager->register(new SSHExecutor());
    }
}
