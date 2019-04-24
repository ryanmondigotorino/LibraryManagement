<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'author', 'namespace' => '\App\Modules\Admin\Author','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','AuthorController@index')->name('admin.author.index');
    Route::get('/add-author','AuthorController@addAuthor')->name('admin.author.add-author');
    Route::post('/add-author-save','AuthorController@addAuthorSave')->name('admin.author.add-author-save');
});
