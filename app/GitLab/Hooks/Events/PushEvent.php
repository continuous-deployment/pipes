<?php

namespace Pipes\GitLab\Hooks\Events;

use Pipes\Hooks\Event;
use Pipes\Models\Commit;
use Pipes\Models\Project;
use Illuminate\Http\Request;

class PushEvent extends GitLabEvent implements Event
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventKey   = 'object_kind';
        $this->eventValue = 'push';
    }

    /**
     * Perform the processing needed on the request
     *
     * @param  Request $request Request object for hook
     * @return void
     */
    public function process(Request $request)
    {
        $data = $request->all();
        $hostId = $this->getHostIdFromRequest($request);

        $parsedUrl  = parse_url(
            $data['repository']['homepage']
        );
        $path = trim($parsedUrl['path'], '/');

        $project = $this->getProject(
            $data['project_id'],
            $path,
            $hostId
        );

        $commits = $this->processCommits(
            $data['commits'],
            $project,
            $data['ref']
        );

        return [
            'project' => $project,
            'commits' => $commits,
            'event'   => [
                'type' => 'push',
            ],
        ];
    }

    /**
     * Processes any commits that exist in data
     *
     * @param  array   $commits Array of commits
     * @param  Project $project Project model
     * @param  string  $branch  Branch these commits came from.
     * @return void
     */
    protected function processCommits($commits, $project, $branch)
    {
        $commitModels = [];

        foreach ($commits as $recievedCommit) {
            $commit = $this->createCommit(
                $recievedCommit['id'],
                $recievedCommit['message'],
                $recievedCommit['url'],
                $recievedCommit['timestamp'],
                $recievedCommit['author']['name'],
                $recievedCommit['author']['email'],
                $branch,
                $project
            );

            $commitModels[] = $commit;
        }

        return $commitModels;
    }
}
