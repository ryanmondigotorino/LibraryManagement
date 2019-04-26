<?php

namespace App\Modules\Admin\Books;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use AuditLogs as AL;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;
use Browser;

class BooksController extends Controller
{
    public static $view_path = "Admin.Books";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(){
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

    public function addbooks(){
        $getAuthors = CF::model('Author')::all();
        return view($this->render('content.add-books'),compact('getAuthors'));
    }

    public function addbooksave(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $frontImage = $request->book_front;
        $backImage = $request->book_back;
        if($frontImage == 'undefined'){
            return array(
                'status' => 'error',
                'messages' => 'No Front Image Uploaded!'
            );
        }elseif($backImage == 'undefined'){
            return array(
                'status' => 'error',
                'messages' => 'No Back Image Uploaded!'
            );
        }else{
            DB::beginTransaction();
            try{
                $bookId = CF::model('Book')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
                $getBookId = $bookId->count() > 0 ? $bookId->get()[0]->id + 1 : 1;
                $front_extension = strtolower($frontImage->extension());
                $front_image_file = $frontImage;
                switch ($front_extension){
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        $frontImageName = 'library_front_books'.time().'.'.$front_extension;
                        break;
                    default:
                        $result['status'] = 'error';
                        $result['messages'] = 'Invalid file type for Front Book Image.';
                        return $result;
                    break;
                }
                $back_extension = strtolower($backImage->extension());
                $back_image_file = $backImage;
                switch ($back_extension){
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        $backImageName = 'library_back_books'.time().'.'.$back_extension;
                        break;
                    default:
                        $result['status'] = 'error';
                        $result['messages'] = 'Invalid file type for Front Book Image.';
                        return $result;
                    break;
                }
                $bookTitle = $request->book_title;
                $books = [
                    'added_by' => $currentLoggedId->id,
                    'front_image' => $frontImageName,
                    'back_image' => $backImageName,
                    'author_id' => $request->book_author,
                    'genre' => $request->book_genre,
                    'title' => $bookTitle,
                    'description' => $request->book_description,
                    'date_published' => strtotime($request->book_published),
                ];
                $result = CF::model('Book')->saveData($books, true);
                Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookId.')',$front_image_file,$frontImageName);
                Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookId.')',$back_image_file,$backImageName);
                AL::audits('admin',$currentLoggedId,$request->ip(),'Add book ('.$bookTitle.')');
                DB::commit();
                return $result;
            }catch(\Exception $e){
                $errors = json_decode($e->getMessage(), true);
                $display_errors = [];
                foreach($errors as $key => $value){
                    $display_errors[] = $value[0];
                }
                $result = [
                    'status' => 'error',
                    'messages' => implode("\n",$display_errors)
                ];
                DB::rollBack();
                return $result;
            }
            Session::flash('message',$result['status']);
            return back();
        }
    }

    public function editbooks(Request $request,$id){
        $getAuthors = CF::model('Author')::all();
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
                'authors.id as author_id',
                'authors.name as author_name',
                'authors.image as author_image',
                'authors.email as author_email',
                'authors.favorite_quote as author_quote'
            )
            ->leftjoin('authors','authors.id','books.author_id')
            ->where('books.id',$id)->get();
        return view($this->render('content.edit-books'),compact('getBooks','getAuthors'));
    }

    public function editbooksave(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $id = $request->book_id;
        $getBookDetails = CF::model('Book')::find($id);
        $frontImage = $request->book_front;
        $backImage = $request->book_back;
        if($frontImage == 'undefined'){
            $frontimageName = $getBookDetails->front_image;
        }else{
            $frontExtension = strtolower($frontImage->extension());
            switch ($frontExtension){
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $frontimageName = 'library_front_books'.time().'.'.$frontExtension;
                    break;
                default:
                    $result['status'] = 'error';
                    $result['messages'] = 'Invalid file type for Front Book Image.';
                    return $result;
                break;
            }
            Storage::disk('uploads')->delete('uploads/book_images/book-('.$getBookDetails->id.')/'.$getBookDetails->front_image);
            Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookDetails->id.')',$frontImage,$frontimageName);
        }
        if($backImage == 'undefined'){
            $backimageName = $getBookDetails->back_image;
        }else{
            $backExtension = strtolower($backImage->extension());
            switch ($backExtension){
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $backimageName = 'library_back_books'.time().'.'.$backExtension;
                    break;
                default:
                    $result['status'] = 'error';
                    $result['messages'] = 'Invalid file type for Back Book Image.';
                    return $result;
                break;
            }
            Storage::disk('uploads')->delete('uploads/book_images/book-('.$getBookDetails->id.')/'.$getBookDetails->back_image);
            Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookDetails->id.')',$backImage,$backimageName);
        }
        $bookTitle = $request->book_title;
        $getBookDetails->author_id = $request->book_author;
        $getBookDetails->front_image = $frontimageName;
        $getBookDetails->back_image = $backimageName;
        $getBookDetails->genre = $request->book_genre;
        $getBookDetails->title = $bookTitle;
        $getBookDetails->description = $request->book_description;
        $getBookDetails->date_published = strtotime($request->book_published);
        $getBookDetails->save();
        AL::audits('admin',$currentLoggedId,$request->ip(),'Edit book ('.$bookTitle.')');
        return [
            'status' => 'success',
            'messages' => 'Book Successfully Updated'
        ];
    }
    
    public function viewbooks(Request $request,$id,$title){
        $getBooks = CF::model('Book')::find($id);
        return view($this->render('content.view-books'));
    }

    public function reservation(){
        return view($this->render('content.reservation'));
    }
}
