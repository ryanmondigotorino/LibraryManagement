<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/student', 'namespace' => '\App\Modules\Student\Home','middleware' => ['web','student','revalidate'],'guard' => 'student'], function(){
    Route::get('/','StudentController@index')->name('student.home.index');
});