<?php

use Illuminate\Support\Facades\Route;
$namespace = "App\Laravel\Controllers\Api";

Route::group(['as' => "api.",
    'namespace' => $namespace,
    'middleware' => ["api"]
],function() {
    Route::get('/','SettingController@health')->name('health');

    Route::group(['prefix' => "checkout",'as' => 'checkout.'],function() {
        Route::get('/', 'CheckoutController@search')->name('search');
        Route::post('/', 'CheckoutController@generate')->name('generate');
    });

    Route::group(['prefix' => "transactions",'as' => 'transaction.'],function() {
        Route::get('/', 'CheckoutController@index')->name('index');
        Route::delete('/', 'TransactionController@destroy')->name('destroy');
        Route::delete('{id}', 'TransactionController@delete')->name('delete');
    });

    Route::group(['prefix' => "auth",'as' => 'auth.'],function(){
        Route::post('register','AuthenticationController@register')->name('register');
        Route::post('login','AuthenticationController@login')->name('login');
        Route::post('logout','AuthenticationController@logout')->name('logout')->middleware(["api.auth"]);
        Route::post('refresh-token','AuthenticationController@refresh_token')->name('refresh_token')->middleware(["api.auth"]);
        Route::post('check-login','AuthenticationController@check_login')->name('check_login')->middleware(["api.auth"]);
    });

    Route::group(['middleware' => "api.auth"],function(){
        Route::group(['prefix' => "profile",'as' => 'profile.'],function(){
            Route::post('info','ProfileController@show')->name('info');
        });

        Route::group(['prefix' => "articles",'as' => 'article.'],function(){
            Route::post('/','ArticleController@index')->name('index');
            Route::post('info','ArticleController@show')->name('info')->middleware(["api.exist:article", "api.exist:own_article"]);
            Route::post('create','ArticleController@store')->name('create');
            Route::post('edit','ArticleController@update')->name('edit')->middleware(["api.exist:article", "api.exist:own_article"]);
            Route::post('delete','ArticleController@destroy')->name('delete')->middleware(["api.exist:article", "api.exist:own_article"]);
        });
    });

});
