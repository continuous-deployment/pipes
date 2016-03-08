<?php

namespace App\GitLab\Hooks\Events;

use App\GitLab\Hooks\HookRegister;
use App\Models\Commit;
use App\Models\Project;
use Illuminate\Http\Request;

abstract class GitLabEvent
{
    /**
     * Key to look for in the request payload.
     * This can be a dot separate to look into arrays
     * contained within the initial array.
     *
     * @var string
     */
    protected $eventKey;

    /**
     * The value that should match what is returned from
     * the $eventKey
     *
     * @var mixed
     */
    protected $eventValue;

    /**
     * Returns whether this event is process the request data
     * and store it into the database if necessary
     *
     * @param  Request $request Request object for hook
     * @return boolean
     */
    public function canProcessRequest(Request $request)
    {
        $hasEventKey = $request->has($this->eventKey);
        if ($hasEventKey === false) {
            return false;
        }

        $isCorrectEvent = $request->get($this->eventKey) == $this->eventValue;

        return $isCorrectEvent;
    }

    /**
     * Gets the host id from the request.
     *
     * @param Request $request Request that has been sent.
     *
     * @return integer
     */
    protected function getHostIdFromRequest(Request $request)
    {
        $routeInfo  = $request->route();
        $parameters = $routeInfo[2];
        $hostId     = $parameters['hostId'];

        return $hostId;
    }

    /**
     * Creates a new commit.
     *
     * @param string  $commitHash  Unique commit hash.
     * @param string  $message     Commit message.
     * @param string  $url         Url of the commit.
     * @param string  $timestamp   Timestamp of when the commit happened.
     * @param string  $authorName  Commit authors name.
     * @param string  $authorEmail Commit authors email.
     * @param string  $branch      Branch the commit came from.
     * @param Project $project     The project to store commit against.
     *
     * @return App\Models\Commit
     */
    protected function createCommit(
        $commitHash,
        $message,
        $url,
        $timestamp,
        $authorName,
        $authorEmail,
        $branch,
        Project $project
    ) {
        // Removing refs/heads/ from the start of the branch.
        $branch = str_replace('refs/heads/', '', $branch);

        $commit = (new Commit())->firstOrCreate(
            [
                'commit_id'    => $commitHash,
                'message'      => $message,
                'url'          => $url,
                'timestamp'    => $timestamp,
                'author_name'  => $authorName,
                'author_email' => $authorEmail,
                'branch'       => $branch,
                'project_id'   => $project->project_id,
            ]
        );

        return $commit;
    }

    /**
     * Gets or create a project from the data given.
     *
     * @param string  $projectId        Id of the project on GitLab.
     * @param string  $namespaceAndName The namespace and name of the project.
     * @param integer $hostId           The hostId to associate to.
     *
     * @return array
     */
    protected function getProject($projectId, $namespaceAndName, $hostId)
    {
        $projectName = explode('/', $namespaceAndName);
        $namespace   = trim($projectName[0]);
        $name        = trim($projectName[1]);

        $project = (new Project())->firstOrCreate([
            'project_id' => $projectId,
            'name'       => $name,
            'group'      => $namespace,
            'host_id'    => $hostId,
        ]);

        $register = new HookRegister();
        $register->registerWithProjectId($projectId, $hostId);

        return $project;
    }
}
