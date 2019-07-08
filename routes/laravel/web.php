<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => '\Tychovbh\Mvc\Http\Controllers'
], function () {
    Route::resource('forms', 'FormController');
    Route::resource('elements', 'ElementController')->only(['index']);
    Route::resource('fields', 'FieldController')->only(['update']);
});
