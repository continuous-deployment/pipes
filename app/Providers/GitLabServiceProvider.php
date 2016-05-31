<?php

namespace Pipes\Providers;

use Pipes\GitLab\Hooks\GitLabHandler;
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
        /** @var \Pipes\Hooks\ServiceRouter $serviceRouter */
        $serviceRouter = $this->app['ServiceRouter'];
        $gitlabHandler = new GitLabHandler();

        $serviceRouter->enrol($gitlabHandler);
    }
}
