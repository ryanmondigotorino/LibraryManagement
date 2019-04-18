<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'books', 'namespace' => '\App\Modules\Admin\Books','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','BooksController@index')->name('admin.books.index');
    Route::get('/reservation','BooksController@reservation')->name('admin.books.reservation');
});
