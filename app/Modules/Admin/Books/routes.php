<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'books', 'namespace' => '\App\Modules\Admin\Books','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','BooksController@index')->name('admin.books.index');
    Route::get('/add-books','BooksController@addbooks')->name('admin.books.add-books');
    Route::post('/add-books-save','BooksController@addbooksave')->name('admin.books.add-books-save');
    Route::get('/reservation','BooksController@reservation')->name('admin.books.reservation');
});
