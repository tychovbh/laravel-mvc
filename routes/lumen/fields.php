<?php
declare(strict_types=1);

$router->put('/fields/{id}', [
    'uses' => 'FieldController@update',
    'as' => 'fields.update',
]);
