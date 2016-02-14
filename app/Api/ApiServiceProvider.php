<?php

namespace App\Api;

use Validator;
use App\Api\V1\Transformers\Validators\Rules\ModelRule;
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
        require __DIR__ . '/V1/Http/routes.php';

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
