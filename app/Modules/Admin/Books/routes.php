<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'books', 'namespace' => '\App\Modules\Admin\Books','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','BooksController@index')->name('admin.books.index');
    Route::get('/add-books','BooksController@addbooks')->name('admin.books.add-books');
    Route::post('/add-books-save','BooksController@addbooksave')->name('admin.books.add-books-save');
    Route::get('/{id}/edit-books','BooksController@editbooks')->name('admin.books.edit-books');
    Route::post('/edit-books-save','BooksController@editbooksave')->name('admin.books.edit-books-save');
    Route::get('/{id}/view-books/{title}','BooksController@viewbooks')->name('admin.books.view-books');
    Route::get('/borrowed','BooksController@borrowed')->name('admin.books.borrowed');
    Route::get('/{slug}/admin-books-get-borrowed','BooksController@getborrowed')->name('admin.books.get-borrowed');
    Route::post('/admin-books-approved-borrowed','BooksController@approvedborrowed')->name('admin.books.approved-borrowed');
    Route::post('/admin-books-delete-borrowed','BooksController@deleteborrowed')->name('admin.books.delete-borrowed');
    Route::post('/admin-books-return-borrowed','BooksController@returnborrowed')->name('admin.books.return-borrowed');
    Route::get('/returned','BooksController@returned')->name('admin.books.returned');
    Route::get('/inventory','BooksController@inventory')->name('admin.books.inventory');
    Route::get('/get-inventory','BooksController@getinventory')->name('admin.books.get-inventory');
    Route::post('/add-quantity-books','BooksController@addquantitybooks')->name('admin.books.add-quantity-books');
    Route::post('/delete-all-quantity','BooksController@deleteallquantity')->name('admin.books.delete-all-quantity');
    Route::post('/disperse-quantity','BooksController@dispersequantity')->name('admin.books.disperse-quantity');
});
