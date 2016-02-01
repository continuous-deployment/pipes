<?php

namespace App\GitLab\Hooks\Events;

use App\GitLab\Hooks\HookRegister;
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
        $data = $request->all();

        // store new project in database
        $projectName = explode('/', $data['path_with_namespace']);
        $namespace   = $projectName[0];
        $name        = $projectName[1];
        $projectId   = $data['project_id'];

        $project             = new Project();
        $project->project_id = $projectId;
        $project->name       = $name;
        $project->group      = $namespace;
        $project->save();

        $register = new HookRegister();
        $register->registerWithProjectId($projectId);

        return [
            'project' => $project,
            'event'   => [
                'type' => 'project_create',
            ],
        ];
    }
}
