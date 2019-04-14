<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'home', 'namespace' => '\App\Modules\Landing\Home','middleware' => ['web']], function(){
    Route::get('/','HomeController@index')->name('landing.home.index');
});