<?php

if (!isset($app)) {
    $app = app();
}

$app->group([
    'namespace' => 'App\Api\V1\Http\Controllers',
    'prefix' => 'api/v1'
], function (Laravel\Lumen\Application $app) {
    $app->post(
        'pipeline',
        [
            'as'   => 'api.v1.pipeline',
            'uses' => 'ApiController@pipeline',
        ]
    );
});

$app->group([
    'namespace' => 'App\Api\V1\Http\Controllers',
    'prefix' => 'api/v1/projects'
], function (Laravel\Lumen\Application $app) {
    $app->get(
        '',
        [
            'as' => 'api.v1.projects',
            'uses' => 'ProjectsController@all'
        ]
    );
});
