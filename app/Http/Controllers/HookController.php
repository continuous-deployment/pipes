<?php

namespace App\Http\Controllers;

use Log;
use App\Api\GitLab\HookRegister;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Commit;

class HookController extends Controller
{
    /**
     * Handle incomming hooks.
     *
     * @param int $id
     *
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
            $projectId = $data['project_id'];

            $project = new Project();
            $project->project_id = $projectId;
            $project->name = $name;
            $project->group = $namespace;
            $project->save();

            $register = new HookRegister();
            $register->registerWithProjectId($projectId);
        }
        if (array_key_exists('object_kind', $data) && $data['object_kind'] == 'push') {
            $project = Project::where('project_id', $data['project_id'])->first();
            if ($project == null) {
                // Removing https:// or http:// so the explode is easier to work with
                $reducedUrl = str_replace(['http://', 'https://'], '', $data['repository']['homepage']);
                $projectName = explode('/', $reducedUrl);
                $namespace = $projectName[1];
                $name = $projectName[2];
                $projectId = $data['project_id'];

                $project = new Project();
                $project->project_id = $projectId;
                $project->name = $name;
                $project->group = $namespace;
                $project->save();
            }


            foreach ($data['commits'] as $recievedCommit) {
                $commit = new Commit();
                $commit->commit_id = $recievedCommit['id'];
                $commit->message = $recievedCommit['message'];
                $commit->url = $recievedCommit['url'];
                $commit->timestamp = $recievedCommit['timestamp'];
                $commit->author_name = $recievedCommit['author']['name'];
                $commit->author_email = $recievedCommit['author']['email'];
                $commit->project_id = $project->project_id;
                $commit->save();
            }
        }

        return $data;
    }
}
