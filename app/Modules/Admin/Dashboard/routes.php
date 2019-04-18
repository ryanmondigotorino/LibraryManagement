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
    Route::get('/librarian-account','DashboardController@librarian')->name('admin.dashboard.accounts.librarian-account');
    Route::post('/change-acc-stat','DashboardController@change_acc_stat')->name('admin.dashboard.accounts.change-acc-stat');

    Route::get('/admin-audit','DashboardController@adminaudit')->name('admin.dashboard.accounts.admin-audit');
    Route::get('/student-audit','DashboardController@studentaudit')->name('admin.dashboard.accounts.student-audit');
    Route::get('/get-admin-logs','DashboardController@getadminlogs')->name('admin.dashboard.accounts.get-admin-logs');
    Route::get('/get-student-logs','DashboardController@getstudentlogs')->name('admin.dashboard.accounts.get-student-logs');
    Route::get('/get-student-logs','DashboardController@getstudentlogs')->name('admin.dashboard.accounts.get-student-logs');
    Route::get('/admin-audit/download-xlsx','DashboardController@admindownloadXlsx')->name('admin.dashboard.accounts.admin-audit.download-xlsx');
    Route::get('/student-audit/download-xlsx','DashboardController@studentdownloadXlsx')->name('admin.dashboard.accounts.student-audit.download-xlsx');
});