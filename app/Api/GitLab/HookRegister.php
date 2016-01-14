<?php

namespace App\Api\GitLab;

use GuzzleHttp;

class HookRegister
{
    /**
     * GitLab instance
     * @var GitLab
     */
    protected $gitlab;

    public function __construct(GitLab $gitlab = null)
    {
        if ($gitlab == null) {
            $gitlab = app(GitLab::class);
        }

        $this->gitlab = $gitlab;
    }

    /**
     * Register project hooks on GitLab
     * @param  string|integer $projectId The id of the project.
     */
    public function registerWithProjectId($projectId)
    {
        $this->gitlab->sendApiRequest(
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
}
