<?php

namespace Pipes\Providers;

use Illuminate\Support\ServiceProvider;
use Pipes\Hooks\ServiceRouter;

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
