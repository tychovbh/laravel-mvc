<?php

$router->group(['namespace' => '\Tychovbh\Mvc\Http\Controllers'], function () use ($router) {
    $router->put('/fields/{id}', [
        'as' => 'fields.update',
        'uses' => 'FieldController@update',
    ]);
});
