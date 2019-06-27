<?php

$this->app->router->group([
    'namespace' => '\Tychovbh\Mvc\Http\Controllers',
], function ($router) {
    require 'fields.php';
    require 'forms.php';
    require 'inputs.php';
});
