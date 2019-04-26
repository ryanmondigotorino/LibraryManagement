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

        $getBooks = CF::model('Book')
        ->select(
            'books.id',
            'books.front_image',
            'books.back_image',
            'books.genre',
            'books.title',
            'books.description',
            'books.date_published',
            'books.created_at',
            'authors.name as author_name',
            'authors.image as author_image',
            'authors.email as author_email',
            'authors.favorite_quote as author_quote'
        )
        ->leftjoin('authors','authors.id','books.author_id');
        return view($this->render('index'),compact('getBooks'));
    }
}
