<?php

namespace Pipes\Providers;

use Validator;
use Pipes\Api\V1\Transformers\Validators\Rules\ModelRule;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        Validator::extend('model', ModelRule::class . '@validate');
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
