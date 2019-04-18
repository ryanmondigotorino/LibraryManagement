<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'course', 'namespace' => '\App\Modules\Admin\Course','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','CourseController@index')->name('admin.course.index');
    Route::get('/get-courses','CourseController@getcourses')->name('admin.course.get-courses');
    Route::post('/add-courses','CourseController@addcourses')->name('admin.course.add-courses');
});
