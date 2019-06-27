<?php

$router->get('/inputs', [
    'as' => 'inputs.index',
    'uses' => 'InputController@index',
]);
