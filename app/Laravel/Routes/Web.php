<?php

use Illuminate\Support\Facades\Route;
$namespace = "App\Laravel\Controllers\Web";

Route::group(['as' => "web.",
    'namespace' => $namespace,
    'middleware' => ["web"]
],function() {
    Route::get('/','MainController@index')->name('home');

    Route::group(['prefix' => "stripe",'as' => 'stripe.'],function() {
        Route::any('cancel/{code}', 'StripeController@cancel')->name('cancel');
        Route::any('{code}', 'StripeController@success')->name('success');
    });
});
