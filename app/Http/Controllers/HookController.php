<?php

namespace App\Http\Controllers;

use Log;
use GuzzleHttp;
use App\Api\GitLab\GitLab;
use App\Api\GitLab\HookRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;

class HookController extends Controller
{
    /**
     * Handle incomming hooks.
     *
     * @param  int  $id
     * @return Response
     */
    public function recieve(Request $request)
    {
        $data = $request->all();
        Log::debug(print_r($data, true));

        if (array_key_exists('event_name', $data) && $data['event_name'] == 'project_create') {
            // store new project in database
            $projectName = explode('/', $data['path_with_namespace']);
            $namespace = $projectName[0];
            $name = $projectName[1];
            $project = new Project;
            $project->name = $name;
            $project->group = $namespace;
            $project->save();

            $projectId = $data['project_id'];
            $register = new HookRegister();
            $register->registerWithProjectId($projectId);
        }

        return $data;
    }
}
