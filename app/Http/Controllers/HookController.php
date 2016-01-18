<?php

namespace App\Http\Controllers;

use Log;
use GuzzleHttp;
use App\Api\GitLab\GitLab;
use App\Api\GitLab\HookRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Commit;

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
        if (array_key_exists('object_kind', $data) && $data['object_kind'] == 'push') {
          foreach($data['commits'] as $recievedCommit){
            $commit = new Commit;
            $commit->commit_id = $commit['id'];
            $commit->message = $commit['message'];
            $commit->url = $commit['url'];
            $commit->timestamp = $commit['timestamp'];
            $commit->author_name = $commit['author']['name'];
            $commit->author_email = $commit['author']['email'];
            $commit->save;
          }
        }

        return $data;
    }
}
