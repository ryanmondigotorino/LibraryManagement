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

class AuthorController extends Controller
{
    public static $view_path = "Admin.Author";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        $getAuthors = CF::model('Author');
        return view($this->render('index'),compact('getAuthors'));
    }

    public function addAuthor(Request $request){
        return view($this->render('content.add-author'));
    }

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
                return $result;
            }catch(\Exception $e){
                return $e;
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
}
