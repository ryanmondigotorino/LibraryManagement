<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/student/contact', 'namespace' => '\App\Modules\Student\Contact', 'middleware' => ['web','student','revalidate'],'guard' => 'student'], function(){
    Route::get('/','ContactController@index')->name('student.contact.index');
});