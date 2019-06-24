<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => '\Tychovbh\Mvc\Http\Controllers'
], function () {
    Route::resource('forms', 'FormController');
    Route::resource('inputs', 'InputController');
});
