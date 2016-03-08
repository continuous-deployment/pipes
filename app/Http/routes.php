<?php
use App\Api\GitLab\GitLabManager;
use App\Models\Stream;
use App\Models\Project;
use App\Models\Condition;
use App\Pipeline\Pipeline;
use App\Pipeline\Traveler\Traveler;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get(
    '/',
    function () use ($app) {
        /** @var \App\Models\Project $project */
        // $project = Project::find(1);
        // $conditions = $project->conditions;
        // foreach ($conditions as $condition) {
        //     $pipeline = new Pipeline();
        //     $stream = new Stream();
        //     $stream->project()->associate($project);
        //     $stream->pipeable()->associate($condition);
        //     $stream->save();
        //
        //     $traveler  = new Traveler($stream);
        //     $traveler->bag->give([
        //         'event' => [
        //             'type' => 'push'
        //         ]
        //     ]);
        //
        //     $pipeline
        //         ->send($traveler)
        //         ->flow($stream);
        // }
        $message = str_replace('Lumen.', 'Pipes.', $app->welcome());
        return $message;
    }
);

// route for easier testing using query strings
$app->get(
    '{appName}/{hostId}/hooks/catch',
    [
        'as'   => 'hook',
        'uses' => 'HookController@recieve',
    ]
);
$app->post(
    '{appName}/{hostId}/hooks/catch',
    [
        'as'   => 'hook',
        'uses' => 'HookController@recieve',
    ]
);

$app->get(
    'test/auth',
    function () {
        $gitlabs = new GitLabManager();
        $gitlab  = collect($gitlabs->getInstances())->last();
        $gitlab->authenticate();
        dd($gitlab);
    }
);

// projects
$app->get(
    '/projects',
    [
        'as'   => 'projects',
        'uses' => 'ProjectController@all',
    ]
);

$app->get(
    '/projects/{projectId}',
    [
        'as'   => 'projects',
        'uses' => 'ProjectController@get',
    ]
);
