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
     * @param  string|integer $hostId    The host id for the GitLab
     * @return void
     */
    public function registerWithProjectId($projectId, $hostId)
    {
        $gitlab = $this->gitlab->getInstanceByHostId($hostId);

        if ($gitlab === null) {
            return;
        }

        $gitlab->sendApiRequest(
            'POST',
            'projects/' . $projectId . '/hooks',
            [
                'id'                    => $projectId,
                'url'                   => $this->getHookCatchUrl($hostId),
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
        /** @var \App\GitLab\GitLab $instance */
        foreach ($this->gitlab->getInstances() as $instance) {
            $instance->sendApiRequest(
                'POST',
                'hooks',
                [
                    'url' => $this->getHookCatchUrlFromCli(
                        $instance->getHostId()
                    ),
                ]
            );
        }
    }

    /**
     * Gets the url for the hook catching
     *
     * @param string|integer $hostId The host id for the GitLab
     *
     * @return string
     */
    protected function getHookCatchUrl($hostId)
    {
        $url = route(
            'hook',
            [
                'appName' => 'gitlab',
                'hostId'  => $hostId,
            ]
        );

        return $url;
    }

    /**
     * Get the hook catch url when access from cli
     *
     * @param string|integer $hostId The host id for the GitLab
     *
     * @return string
     */
    protected function getHookCatchUrlFromCli($hostId)
    {
        $url  = $this->getHookCatchUrl($hostId);
        $path = str_replace(['http://:', 'https://:'], '', $url);
        $url  = env('PIPES_URL') . $path;

        return $url;
    }
}
