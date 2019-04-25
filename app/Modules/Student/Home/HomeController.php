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

class HomeController extends Controller
{
    public static $view_path = "Student.Home";

    public function index(Request $request){
        $base_data = Auth::guard('student')->user();
        $getuserdata = CF::model('Student')::find($base_data->id);
        if($base_data->account_line == 0){
            $getuserdata->account_line = 1;
            $getuserdata->save();
        }
        return view($this->render('index'));
    }
}
