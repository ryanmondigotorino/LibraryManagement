<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reports', 'namespace' => '\App\Modules\Admin\Reports','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','ReportsController@index')->name('admin.reports.index');
});
