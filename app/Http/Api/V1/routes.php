<?php

use Illuminate\Routing\Router;

$router->post(
    'pipeline',
    [
        'as'   => 'api.v1.pipeline.store',
        'uses' => 'PipelineController@storePipeline',
    ]
);
$router->get(
    'pipeline/{conditionId}',
    [
        'as'   => 'api.v1.pipeline',
        'uses' => 'PipelineController@pipeline',
    ]
);

$router->group([
    'prefix' => 'projects'
], function (Router $router) {
    $router->get(
        '',
        [
            'as' => 'api.v1.projects',
            'uses' => 'ProjectsController@all'
        ]
    );

    $router->get(
        '{project_id}',
        [
            'as' => 'api.v1.project',
            'uses' => 'ProjectsController@get'
        ]
    );

    $router->post(
        'store',
        [
            'as' => 'api.v1.projects.store',
            'uses' => 'ProjectsController@store'
        ]
    );

    $router->patch(
        '{project_id}/update',
        [
            'as' => 'api.v1.projects.update',
            'uses' => 'ProjectsController@update'
        ]
    );

    $router->delete(
        '{project_id}/delete',
        [
            'as' => 'api.v1.projects.delete',
            'uses' => 'ProjectsController@delete'
        ]
    );
});
