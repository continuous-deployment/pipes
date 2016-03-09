<?php

if (!isset($app)) {
    $app = app();
}

$app->group([
    'namespace' => 'App\Api\V1\Http\Controllers',
    'prefix' => 'api/v1',
    'middleware' => 'cors'
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
    'prefix' => 'api/v1/projects',
    'middleware' => 'cors'
], function (Laravel\Lumen\Application $app) {
    $app->get(
        '',
        [
            'as' => 'api.v1.projects',
            'uses' => 'ProjectsController@all'
        ]
    );

    $app->get(
        '{project_id}',
        [
            'as' => 'api.v1.project',
            'uses' => 'ProjectsController@get'
        ]
    );

    $app->post(
        'store',
        [
            'as' => 'api.v1.projects.store',
            'uses' => 'ProjectsController@store'
        ]
    );

    $app->patch(
        '{project_id}/update',
        [
            'as' => 'api.v1.projects.update',
            'uses' => 'ProjectsController@update'
        ]
    );

    $app->delete(
        '{project_id}/delete',
        [
            'as' => 'api.v1.projects.delete',
            'uses' => 'ProjectsController@delete'
        ]
    );
});

$app->group([
    'namespace' => 'App\Api\V1\Http\Controllers',
    'prefix' => 'api/v1/hosts',
    'middleware' => 'cors'
], function (Laravel\Lumen\Application $app) {
    $app->get(
        '',
        [
            'as' => 'api.v1.hosts',
            'uses' => 'HostsController@all'
        ]
    );
    $app->get(
        '{host_id}',
        [
            'as' => 'api.v1.host',
            'uses' => 'HostsController@get'
        ]
    );
    $app->post(
        'store',
        [
            'as' => 'api.v1.hosts.store',
            'uses' => 'HostsController@store'
        ]
    );

    $app->patch(
        '{host_id}/update',
        [
            'as' => 'api.v1.hosts.update',
            'uses' => 'HostsController@update'
        ]
    );

    $app->delete(
        '{host_id}/delete',
        [
            'as' => 'api.v1.hosts.delete',
            'uses' => 'HostsController@delete'
        ]
    );
});
