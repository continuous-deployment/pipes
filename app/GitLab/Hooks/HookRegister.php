<?php

namespace App\GitLab\Hooks;

use GuzzleHttp;
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
     * @param GitLabManager $gitlab accepts a GitLabManager instance.
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
                'build_events'          => true
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
                'url' => $this->getHookCatchUrl(true)
            ]
        );
    }

    /**
     * Gets the url for the hook catching
     */
    protected function getHookCatchUrl($fromCli = false)
    {
        $url = route('hook', ['appName' => 'gitlab']);

        if ($fromCli) {
            $path = str_replace(['http://:', 'https://:'], '', $url);
            $url = env('PIPES_URL') . $path;
        }

        return $url;
    }
}
