<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'course', 'namespace' => '\App\Modules\Admin\Course','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','CourseController@index')->name('admin.course.index');
});
