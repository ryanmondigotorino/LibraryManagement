<?php

namespace App\Modules\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;

class DashboardController extends Controller
{
    public static $view_path = "Admin.Dashboard";

    public function index(Request $request){
        $base_data = Auth::guard('admin')->user();
        return $base_data;
        // return view($this->render('index'));
    }
}
