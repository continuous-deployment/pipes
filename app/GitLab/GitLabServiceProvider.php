<?php

namespace App\GitLab;

use App\GitLab\Hooks\GitLabHandler;
use Illuminate\Support\ServiceProvider;

class GitLabServiceProvider extends ServiceProvider
{
    /**
    * Register bindings in the container.
    *
    * @return void
    */
    public function register()
    {
        /** @var \App\Hooks\ServiceRouter $serviceRouter */
        $serviceRouter = $this->app['ServiceRouter'];
        $gitlabHandler = new GitLabHandler();

        $serviceRouter->enrol($gitlabHandler);
    }
}
