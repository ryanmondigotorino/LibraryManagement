<?php

namespace App\Modules\Admin\Books;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;

class BooksController extends Controller
{
    public static $view_path = "Admin.Books";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(){
        $getBooks = CF::model('Book');
        return view($this->render('index'),compact('getBooks'));
    }

    public function addbooks(){
        return view($this->render('content.add-books'));
    }

    public function addbooksave(Request $request){
        $currentLoggedId = Auth::guard('admin')->user()->id;
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
                        $result['messages'] = 'Invalid File Type';
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
                        $result['messages'] = 'Invalid File Type';
                        return $result;
                    break;
                }

                $books = [
                    'added_by' => $currentLoggedId,
                    'front_image' => $frontImageName,
                    'back_image' => $backImageName,
                    'author' => $request->book_author,
                    'genre' => $request->book_genre,
                    'title' => $request->book_title,
                    'description' => $request->book_description,
                    'date_published' => strtotime($request->book_published),
                ];
                $result = CF::model('Book')->saveData($books, true);
                Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookId.')',$front_image_file,$frontImageName);
                Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookId.')',$back_image_file,$backImageName);
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

    public function reservation(){
        return view($this->render('content.reservation'));
    }
}
