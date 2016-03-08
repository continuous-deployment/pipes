<?php

namespace App\Hooks;

use Illuminate\Support\ServiceProvider;

class HooksServiceProvider extends ServiceProvider
{
    /**
    * Register bindings in the container.
    *
    * @return void
    */
    public function register()
    {
        $serviceRouter = new ServiceRouter();
        $this->app->instance('ServiceRouter', $serviceRouter);
    }
}
