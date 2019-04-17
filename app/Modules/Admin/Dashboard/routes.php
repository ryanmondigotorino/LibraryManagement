<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'namespace' => '\App\Modules\Admin\Dashboard','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','DashboardController@index')->name('admin.dashboard.index');
});

Route::group(['prefix' => 'accounts', 'namespace' => '\App\Modules\Admin\Dashboard', 'middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/admin-account','DashboardController@admins')->name('admin.dashboard.accounts.admins-account');
    Route::get('/get-admin-account','DashboardController@getadmins')->name('admin.dashboard.accounts.get-admins-account');
    Route::get('/student-account','DashboardController@students')->name('admin.dashboard.accounts.students-account');
    Route::get('/get-students-account','DashboardController@getstudents')->name('admin.dashboard.accounts.get-students-account');
});