<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/student/explore', 'namespace' => '\App\Modules\Student\Explore','middleware' => ['web','student','revalidate'],'guard' => 'student'], function(){
    Route::get('/','ExploreController@index')->name('student.explore.index');
    Route::get('/{id}/view-book','ExploreController@viewbook')->name('student.explore.view-book');   
    Route::get('/borrowed-books','ExploreController@borrowedBooks')->name('student.explore.borrowed-books');   
    Route::get('/get-borrowed-books','ExploreController@getBorrowedBooks')->name('student.explore.get-borrowed-books');   
    Route::post('/borrowed-books-save','ExploreController@borrowedBooksSave')->name('student.explore.borrowed-books-save');   
});