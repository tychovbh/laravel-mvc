<?php
declare(strict_types=1);

$router->get('/elements', [
    'as' => 'elements.index',
    'uses' => 'ElementController@index',
]);
