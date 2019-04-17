<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/', 'namespace' => '\App\Modules\Landing\Home','middleware' => ['web']], function(){
    Route::get('/','HomeController@index')->name('landing.home.index');
    Route::get('sign-up','HomeController@signup')->name('landing.home.sign-up');
    Route::get('/login','HomeController@login')->name('landing.home.login');
    Route::post('/login-submit','HomeController@loginsubmit')->name('landing.home.login-submit');
    Route::post('/sign-up-submit','HomeController@signupsubmit')->name('landing.home.sign-up-submit');
    Route::post('/logout','HomeController@logout')->name('landing.home.logout');
});