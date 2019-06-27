<?php
$router->put('/fields/{id}', [
    'uses' => 'FieldController@update',
    'as' => 'fields.update',
]);
