<?php

$router->group(['namespace' => '\Tychovbh\Mvc\Http\Controllers'], function () use ($router) {
    $router->get('/inputs', [
        'as' => 'inputs.index',
        'uses' => 'InputController@index',
    ]);
});
