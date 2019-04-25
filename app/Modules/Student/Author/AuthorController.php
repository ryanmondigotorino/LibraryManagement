<?php

namespace App\Modules\Student\Author;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;

class AuthorController extends Controller
{
    public static $view_path = "Student.Author";

    public function index(Request $request){
        if($request->search){
            $searched = $request->search;
            $getAuthors = CF::model('Author')
                ->where(function($query) use ($request,$searched){
                    $query->orWhere('name','like',"%".$searched."%");
                    $query->orWhere('email','like',"%".$searched."%");
                    $query->orWhere('favorite_quote','like',"%".$searched."%");
                });
            $placeholder = $searched;
        }else{
            $getAuthors = CF::model('Author');
            $placeholder = '';
        }
        return view($this->render('index'),compact('getAuthors','placeholder'));
    }
}
