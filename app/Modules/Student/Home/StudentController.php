<?php

namespace App\Modules\Student\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;

class StudentController extends Controller
{
    public static $view_path = "Student.Home";

    public function index(Request $request){
        $base_data = Auth::guard('student')->user();
        return $base_data;
    }
}
