<?php
use App\Api\GitLab\GitLabManager;

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

$app->get('/', function () use ($app) {
    return $app->welcome();
});

// route for easier testing using query strings
$app->get('/hooks/catch', [
    'as' => 'hook', 'uses' => 'HookController@recieve'
]);
$app->post('/hooks/catch', [
    'as' => 'hook', 'uses' => 'HookController@recieve'
]);

$app->get('test/auth', function () {
    $gitlabs = new GitLabManager();
    $gitlab = collect($gitlabs->getInstances())->last();
    $gitlab->authenticate();
    dd($gitlab);
});
