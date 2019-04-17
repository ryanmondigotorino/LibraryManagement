<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/admin', 'namespace' => '\App\Modules\Admin\Dashboard','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','DashboardController@index')->name('admin.dashboard.index');
});