<?php

namespace App\GitLab;

use App\GitLab\Hooks\GitLabCatcher;
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
        /** @var \App\Hooks\Pier $pier */
        $pier = $this->app['Pier'];
        $gitlabCatcher = new GitLabCatcher();

        $pier->enrol($gitlabCatcher);
    }
}
