<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/', 'namespace' => '\App\Modules\Landing\Home','middleware' => ['web']], function(){
    Route::get('/','HomeController@index')->name('landing.home.index');
    Route::get('/sign-up','HomeController@signup')->name('landing.home.sign-up');
    Route::get('/sign-up/account-verification/{userName}','HomeController@accountVerification')->name('landing.home.sign-up-account-verification');
    Route::get('/login','HomeController@login')->name('landing.home.login');
    Route::post('/login-submit','HomeController@loginsubmit')->name('landing.home.login-submit');
    Route::post('/sign-up-submit','HomeController@signupsubmit')->name('landing.home.sign-up-submit');
    Route::post('/logout','HomeController@logout')->name('landing.home.logout');
    Route::get('/forgotpassword','HomeController@forgotpassword')->name('landing.home.forgotpassword');
    Route::post('/forgotpassword-email','HomeController@forgotpasswordemail')->name('landing.home.forgotpassword-email');
    Route::get('/{id}/new-password/{studno}','HomeController@newpassword')->name('landing.home.new-password');
    Route::post('/{id}/new-password-submit/{studno}','HomeController@newpasswordsubmit')->name('landing.home.new-password-submit');
});