<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/student/author', 'namespace' => '\App\Modules\Student\Author','middleware' => ['web','student','revalidate'],'guard' => 'student'], function(){
    Route::get('/','AuthorController@index')->name('student.author.index');
});