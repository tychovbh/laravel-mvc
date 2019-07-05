<?php
declare(strict_types=1);

$router->get('/inputs', [
    'as' => 'inputs.index',
    'uses' => 'InputController@index',
]);
