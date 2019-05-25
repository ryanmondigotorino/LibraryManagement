<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/student/profile', 'namespace' => '\App\Modules\Student\Home','middleware' => ['web','student','revalidate'],'guard' => 'student'], function(){
    Route::get('/','HomeController@index')->name('student.home.index');
    Route::get('/{slug}','HomeController@profile')->name('student.home.profile');
    Route::post('/{slug}/image-upload','HomeController@imageUpload')->name('student.home.profile-image-upload');
    Route::get('/{slug}/settings','HomeController@accountsettings')->name('student.home.profile-settings');
    Route::post('/{slug}/edit-settings-save','HomeController@editsettingssave')->name('student.home.edit-settings-save');
});