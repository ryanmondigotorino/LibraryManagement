<?php

namespace App\Modules\Admin\Author;

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
use Validator;

/**
 * ---------------------------------------------------------------------------
 * - AuthorController This class controller is for Author pages and transactions 
 * - in administrator page
 * ---------------------------------------------------------------------------
 */

class AuthorController extends Controller
{
    public static $view_path = "Admin.Author";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }
    
    /* Gets the list of all authors */
    
    public function index(Request $request){
        $getAuthors = CF::model('Author');
        return view($this->render('index'),compact('getAuthors'));
    }

    /* Add the author's information  */

    public function addAuthor(Request $request){
        return view($this->render('content.add-author'));
    }

    /* Saves the author's information */

    public function addAuthorSave(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $image = $request->authorImage;
        if($image == 'undefined'){
            return array(
                'status' => 'error',
                'messages' => 'No Front Image Uploaded!'
            );
        }else{
            DB::beginTransaction();
            try{
                $authorId = CF::model('Author')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
                $getauthorId = $authorId->count() > 0 ? $authorId->get()[0]->id + 1 : 1;
                $extension = strtolower($image->extension());
                switch ($extension){
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        $imageName = 'library-authors-'.time().'.'.$extension;
                        break;
                    default:
                        $result['status'] = 'error';
                        $result['messages'] = 'Invalid file type for Front Book Image.';
                        return $result;
                    break;
                }
                $authorName = $request->author_name;
                $authors = [
                    'name' => $authorName,
                    'image' => $imageName,
                    'email' => $request->author_email,
                    'favorite_quote' => $request->quote,
                ];
                $result = CF::model('Author')->saveData($authors, true);
                Storage::disk('uploads')->putFileAs('uploads/authors/author-('.$getauthorId.')',$image,$imageName);
                AL::audits('admin',$currentLoggedId,$request->ip(),'Add author ('.$authorName.')');
                DB::commit();
                $result['url'] = route('admin.author.index');
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

    /* Edits the author's information */

    public function editAuthor(Request $request,$id){
        $getAuthors = CF::model('Author')::find($id);
        return view($this->render('content.edit-author'),compact('getAuthors'));
    }

    /* Save the edited author's information */

    public function editAuthorSave(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $id = $request->author_id;
        $getAuthorDetails = CF::model('Author')::find($id);
        $image = $request->authorImage;
        $validator = Validator::make($request->all(),[
            'author_name' => 'required',
            'author_email' => 'required',
        ]);
        if($validator->fails()){
            return array(
                'status' => 'error',
                'messages' => $validator->errors()->first()
            );
        }
        if($image == 'undefined'){
            $imageName = $getAuthorDetails->image;
        }else{
            $extension = strtolower($image->extension());
            switch ($extension){
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $imageName = 'library-authors-'.time().'.'.$extension;
                    break;
                default:
                    $result['status'] = 'error';
                    $result['messages'] = 'Invalid file type for Front Book Image.';
                    return $result;
                break;
            }
            Storage::disk('uploads')->delete('uploads/authors/author-('.$getAuthorDetails->id.')/'.$getAuthorDetails->image);
            Storage::disk('uploads')->putFileAs('uploads/authors/author-('.$getAuthorDetails->id.')',$image,$imageName);
        }
        $authorName = $request->author_name;
        $getAuthorDetails->image = $imageName;
        $getAuthorDetails->name = $authorName;
        $getAuthorDetails->email = $request->author_email;
        $getAuthorDetails->favorite_quote = $request->quote;
        $getAuthorDetails->save();
        AL::audits('admin',$currentLoggedId,$request->ip(),'Edit author ('.$authorName.')');
        return array(
            'status' => 'success',
            'messages' => 'Author successfully Updated',
            'url' => route('admin.author.index')
        );
    }

    /* Views the specific author's information */

    public function viewAuthor(Request $request,$id,$slug){
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
