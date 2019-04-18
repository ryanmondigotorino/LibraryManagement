<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'department', 'namespace' => '\App\Modules\Admin\Department','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','DepartmentController@index')->name('admin.department.index');
    Route::get('/get-department','DepartmentController@getdepartment')->name('admin.department.get-department');
    Route::post('/add-department','DepartmentController@adddepartment')->name('admin.department.add-department');
});
