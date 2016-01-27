<?php

namespace App\GitLab\Hooks\Events;

use App\Hooks\Event;
use App\Models\Commit;
use App\Models\Project;
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

        $project = Project::where('project_id', $data['project_id'])->first();
        if ($project === null) {
            // Removing protocols so the explode is easier to work with
            $reducedUrl  = $this->removeProtocol(
                $data['repository']['homepage']
            );
            $projectName = explode('/', $reducedUrl);
            $namespace   = $projectName[1];
            $name        = $projectName[2];
            $projectId   = $data['project_id'];

            $project             = new Project();
            $project->project_id = $projectId;
            $project->name       = $name;
            $project->group      = $namespace;
            $project->save();
        }

        $this->processCommits($data['commits'], $project);
    }

    /**
     * Removes http protocols from the start of the url
     *
     * @param  string $url Url to remove protocol from
     * @return string
     */
    protected function removeProtocol($url)
    {
        $reducedUrl = str_replace(
            ['http://', 'https://'],
            '',
            $url
        );

        return $reducedUrl;
    }

    /**
     * Processes any commits that exist in data
     *
     * @param  array   $commits Array of commits
     * @param  Project $project Project model
     * @return void
     */
    protected function processCommits($commits, $project)
    {
        foreach ($commits as $recievedCommit) {
            $commit               = new Commit();
            $commit->commit_id    = $recievedCommit['id'];
            $commit->message      = $recievedCommit['message'];
            $commit->url          = $recievedCommit['url'];
            $commit->timestamp    = $recievedCommit['timestamp'];
            $commit->author_name  = $recievedCommit['author']['name'];
            $commit->author_email = $recievedCommit['author']['email'];
            $commit->project_id   = $project->project_id;
            $commit->save();
        }
    }
}
