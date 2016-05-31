<?php
use Pipes\GitLab\GitLabManager;

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

$router->get(
    '/',
    function () {
        return view('welcome');
    }
);

// route for easier testing using query strings
$router->get(
    '{appName}/{hostId}/hooks/catch',
    [
        'as'   => 'hook',
        'uses' => 'HookController@recieve',
    ]
);
$router->post(
    '{appName}/{hostId}/hooks/catch',
    [
        'as'   => 'hook',
        'uses' => 'HookController@recieve',
    ]
);

$router->get(
    'test/auth',
    function () {
        $gitlabs = new GitLabManager();
        $gitlab  = collect($gitlabs->getInstances())->last();
        $gitlab->authenticate();
        dd($gitlab);
    }
);

// projects
$router->get(
    '/projects',
    [
        'as'   => 'projects',
        'uses' => 'ProjectController@all',
    ]
);

$router->get(
    '/projects/{projectId}',
    [
        'as'   => 'projects',
        'uses' => 'ProjectController@get',
    ]
);
