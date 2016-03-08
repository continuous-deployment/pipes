<?php

namespace App\GitLab\Hooks\Events;

use App\Hooks\Event;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectCreateEvent extends GitLabEvent implements Event
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventKey   = 'event_name';
        $this->eventValue = 'project_create';
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
        $data   = $request->all();

        // store new project in database
        $project = $this->getProject(
            $data['project_id'],
            $data['path_with_namespace'],
            $hostId
        );

        return [
            'project' => $project,
            'event'   => [
                'type' => 'project_create',
            ],
        ];
    }
}
