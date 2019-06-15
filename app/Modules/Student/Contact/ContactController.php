<?php

namespace App\Modules\Student\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;

/**
 * ---------------------------------------------------------------------------
 * - ContactController This class controller is for Contact page
 * - in student page
 * ---------------------------------------------------------------------------
 */

class ContactController extends Controller
{
    public static $view_path = "Student.Contact";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        return view($this->render('index'));
    }
}


