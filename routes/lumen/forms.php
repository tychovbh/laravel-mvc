<?php

$router->group(['namespace' => '\Tychovbh\Mvc\Http\Controllers'], function () use ($router) {
    $router->get('/forms', [
        'as' => 'forms.index',
        'uses' => 'FormController@index',
    ]);

    $router->get('/forms/{id}', [
        'as' => 'forms.show',
        'uses' => 'FormController@show',
    ]);

    $router->post('/forms', [
        'as' => 'forms.store',
        'uses' => 'FormController@store',
    ]);

    $router->put('/forms/{id}', [
        'as' => 'forms.update',
        'uses' => 'FormController@update',
    ]);

    $router->delete('/forms/{id}', [
        'as' => 'forms.destroy',
        'uses' => 'FormController@destroy',
    ]);

});
