<?php
use App\Api\GitLab\GitLabManager;
use App\Models\Condition;
use App\Pipeline\Pipeline;
use App\Pipeline\Traveler;

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
        /** @var \App\Models\Condition $condition */
        $condition = Condition::find(1);
        $traveler  = new Traveler();
        $traveler->give([
            'event' => [
                'type' => 'push',
            ],
        ]);
        $pipeline  = new Pipeline();

        $pipeline
            ->send($traveler)
            ->startWithModel($condition);

        return $app->welcome();
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
