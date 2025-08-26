<?php

use Illuminate\Support\Facades\Route;
$namespace = "App\Laravel\Controllers\Portal";

Route::group(['as' => "web.",
    'namespace' => $namespace,
    'prefix' => "portal",
    'middleware' => ["web"]
],function() {
    Route::get('/','MainController@index')->name('home');;
});
