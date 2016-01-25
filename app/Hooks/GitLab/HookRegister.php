<?php

namespace App\Hooks\GitLab;

use GuzzleHttp;
use App\Hooks\GitLab\GitLabManager;

class HookRegister
{
    /**
     * GitLabManager instance
     * @var GitLabManager
     */
    protected $gitlab;

    /**
     * Constructor
     * @param GItLabManager $gitlab
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
     */
    public function registerWithProjectId($projectId)
    {
        $this->gitlab->sendApiRequestToInstances(
            'POST',
            'projects/' . $projectId . '/hooks',
            [
                'id'                    => $projectId,
                'url'                   => env('PIPES_URL') . '/hooks/catch',
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
     */
    public function registerSystemHooksOnInstances()
    {
        $this->gitlab->sendApiRequestToInstances(
            'POST',
            'hooks',
            [
                'url' => env('PIPES_URL') . '/hooks/catch'
            ]
        );
    }
}
