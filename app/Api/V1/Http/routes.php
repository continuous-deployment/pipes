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
            'as'   => 'api.v1.pipeline.store',
            'uses' => 'PipelineController@storePipeline',
        ]
    );
    $app->get(
        'pipeline/{conditionId}',
        [
            'as'   => 'api.v1.pipeline',
            'uses' => 'PipelineController@pipeline',
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
