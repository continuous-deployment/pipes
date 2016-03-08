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
