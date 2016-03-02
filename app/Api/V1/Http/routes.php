<?php

if (!isset($app)) {
    $app = app();
}

$app->group([
    'namespace' => 'App\Api\V1\Http\Controllers',
    'prefix' => 'api/v1'
], function () use ($app) {
    $app->post(
        'pipeline',
        [
            'as'   => 'api.v1.pipeline',
            'uses' => 'ApiController@pipeline',
        ]
    );
});
