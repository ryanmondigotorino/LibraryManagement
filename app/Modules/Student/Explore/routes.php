<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/student/explore', 'namespace' => '\App\Modules\Student\Explore','middleware' => ['web','student','revalidate'],'guard' => 'student'], function(){
    Route::get('/','ExploreController@index')->name('student.explore.index');
});