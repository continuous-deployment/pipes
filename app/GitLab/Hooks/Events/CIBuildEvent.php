<?php

namespace Pipes\GitLab\Hooks\Events;

use Pipes\Hooks\Event;
use Pipes\Models\Project;
use Illuminate\Http\Request;

class CIBuildEvent extends GitLabEvent implements Event
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventKey   = 'object_kind';
        $this->eventValue = 'build';
    }

    /**
     * Perform the processing needed on the request
     *
     * @param  Request $request Request object for hook
     * @return void
     */
    public function process(Request $request)
    {
        $hostId = $this->getHostIdFromRequest($request);

        $data        = $request->all();
        $projectId   = $data['project_id'];

        $project = $this->getProject(
            $projectId,
            $data['project_name'],
            $hostId
        );

        $commit = $data['commit'];

        $this->createCommit(
            $commit['sha'],
            $commit['message'],
            '',
            $commit['started_at'],
            $commit['author_name'],
            $commit['author_email'],
            $data['ref'],
            $project
        );

        return [
            'project' => $project,
            'event'   => [
                'type' => 'ci_build',
            ],
            'build' => [
                'name'   => $data['build_name'],
                'stage'  => $data['build_stage'],
                'status' => $data['build_status'],
            ],
        ];
    }
}
