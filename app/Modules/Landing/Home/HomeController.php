<?php

namespace App\Modules\Landing\Home;

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
    public static $view_path = "Landing.Home";

    public function index(Request $request){
        return view($this->render('index'));
    }
}
