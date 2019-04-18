<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'department', 'namespace' => '\App\Modules\Admin\Department','middleware' => ['web','admin','revalidate'],'guard' => 'admin'], function(){
    Route::get('/','DepartmentController@index')->name('admin.department.index');
});
