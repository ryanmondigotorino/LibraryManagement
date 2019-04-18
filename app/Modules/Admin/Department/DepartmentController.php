<?php

namespace App\Modules\Admin\Department;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;

class DepartmentController extends Controller
{
    public static $view_path = "Admin.Department";

    public function index(Request $request){
        return view($this->render('index'));
    }
}
