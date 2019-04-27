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
    public function viewauthor(Request $request,$id,$slug){
        $getAuthors = CF::model('Author')::find($id);
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
            ->leftjoin('authors','authors.id','books.author_id')
            ->where('authors.id',$id);
        return view($this->render('content.view-author'),compact('getAuthors','getBooks'));
    }
}
