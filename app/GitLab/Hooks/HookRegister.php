<?php

namespace App\GitLab\Hooks;

use App\GitLab\GitLabManager;

class HookRegister
{
    /**
     * GitLabManager instance
     * @var GitLabManager
     */
    protected $gitlab;

    /**
     * Constructor
     * @param  GitLabManager $gitlab accepts a GitLabManager instance.
     * @return void
     */
    public function __construct(GitLabManager $gitlab = null)
    {
        if ($gitlab == null) {
            $gitlab = app(GitLabManager::class);
        }

        $this->gitlab = $gitlab;
    }

    /**
     * Register project hooks on GitLab
     * @param  string|integer $projectId The id of the project.
     * @return void
     */
    public function registerWithProjectId($projectId)
    {
        $this->gitlab->sendApiRequestToInstances(
            'POST',
            'projects/' . $projectId . '/hooks',
            [
                'id'                    => $projectId,
                'url'                   => $this->getHookCatchUrl(),
                'push_events'           => true,
                'issues_events'         => true,
                'merge_requests_events' => true,
                'tag_push_events'       => true,
                'note_events'           => true,
                'build_events'          => true,
            ]
        );
    }

    /**
     * Register system hooks on all the gitlab instances
     * @return void
     */
    public function registerSystemHooksOnInstances()
    {
        $this->gitlab->sendApiRequestToInstances(
            'POST',
            'hooks',
            [
                'url' => $this->getHookCatchUrlFromCli(),
            ]
        );
    }

    /**
     * Gets the url for the hook catching
     *
     * @return string
     */
    protected function getHookCatchUrl()
    {
        $url = route('hook', ['appName' => 'gitlab']);

        return $url;
    }

    /**
     * Get the hook catch url when access from cli
     *
     * @return string
     */
    protected function getHookCatchUrlFromCli()
    {
        $url  = $this->getHookCatchUrl();
        $path = str_replace(['http://:', 'https://:'], '', $url);
        $url  = env('PIPES_URL') . $path;

        return $url;
    }
}
