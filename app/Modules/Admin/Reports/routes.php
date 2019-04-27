<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reports', 'namespace' => '\App\Modules\Admin\Reports','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','ReportsController@index')->name('admin.reports.index');
    Route::get('/get-returned-books-chart','ReportsController@getReturnedBooksChart')->name('admin.reports.get-returned-books-chart');
    Route::get('/get-returned-books','ReportsController@getReturnBooks')->name('admin.reports.get-returned-books');
    Route::get('/download-xlsx','ReportsController@downloadxlsx')->name('admin.reports.download-xlsx');
});
