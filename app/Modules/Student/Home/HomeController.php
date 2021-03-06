<?php

namespace App\Modules\Student\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use AuditLogs as AL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use Auth;
use View;
use DB;
use URL;
use Validator;

/**
 * ---------------------------------------------------------------------------
 * - HomeController The HomeController of student handles the index page of 
 * - Student page, also the profile and account settings of the user.
 * ---------------------------------------------------------------------------
 */

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
        if($request->search){
            $searched = $request->search;
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
                ->whereNull('books.status')
                ->leftjoin('authors','authors.id','books.author_id')
                ->where(function($query) use ($request,$searched){
                    $query->orWhere('books.genre','like',"%".$searched."%");
                    $query->orWhere('books.title','like',"%".$searched."%");
                    $query->orWhere('books.description','like',"%".$searched."%");
                    $query->orWhere('authors.email','like',"%".$searched."%");
                    $query->orWhere('authors.name','like',"%".$searched."%");
                })
                ->orderBy('books.id','desc')->paginate(12);
            $placeholder = $searched;
        }else{
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
                ->whereNull('books.status')
                ->leftjoin('authors','authors.id','books.author_id')
                ->orderBy('books.id','desc')->paginate(12);
            $placeholder = '';
        }
        if(isset($request->page) || $request->page > 1){
            return view('Admin.includes.paginate-card-books',compact('getBooks'));
        }else{
            return view($this->render('index'),compact('getBooks'));
        }
    }

    public function profile(Request $request,$slug){
        return view($this->render('content.profile'));
    }

    public function imageUpload(Request $request){
        $userData = CF::model('Student')::find($request->id);
        if($userData['image'] != 'noimage.png' || $userData['image'] != null){
            $result = Storage::disk('uploads')->delete('uploads/profile_image/user('.$userData['id'].')/'.$userData['image']);
        }
        $data = [];
        $data['id'] = $request->id;
        $extension = strtolower($request->image_profile->extension());
        $image_file = $request->image_profile;
        $result = [];

        switch ($extension){
            case 'jpg':
            case 'jpeg':
            case 'png':
                $imageName = 'library_management_'.time().'.'.$extension;
                break;
            default:
                    $result['status'] = 'error';
                    $result['msg'] = 'Invalid File Type';
                    return $result;
                break;
        }
        $data['image'] = $imageName;
        Storage::disk('uploads')->putFileAs('uploads/profile_image/user('.$userData['id'].')',$image_file,$imageName);
        CF::model('Student')->saveData($data);
        AL::audits('student',$userData,$request->ip(),'Change my profile picture');
    }

    public function accountsettings(Request $request){
        $getCourses = CF::model('Course')
            ->where('course_status','up')
            ->get();
        return view($this->render('content.account-settings'),compact('getCourses'));
    }

    public function editsettingssave(Request $request){
        $base_data = Auth::guard('student')->user();
        $getUserData = CF::model('Student')::find($base_data->id);
        if(!isset($request->middlename) || $request->middlename == ''){
            $validator = Validator::make($request->all(),[
                'firstname' => 'required|regex:/^[a-zA-Z]+$/u',
                'lastname' => 'required|regex:/^[a-zA-Z]+$/u',
                'username' => 'required',
                'contact_number' => 'required|numeric'
            ]);
        }else{
            $validator = Validator::make($request->all(),[
                'firstname' => 'required|regex:/^[a-zA-Z]+$/u',
                'middlename' => 'regex:/^[a-zA-Z]+$/u',
                'lastname' => 'required|regex:/^[a-zA-Z]+$/u',
                'username' => 'required',
                'contact_number' => 'required|numeric'
            ]);
        }
        if($validator->fails()){
            return array(
                'status' => 'error',
                'messages' => $validator->errors()->first()
            );
        }elseif(strlen($request->contact_number) != 11){
            return array(
                'status' => 'error',
                'messages' => 'Contact number must be at least 11 digits.'
            );
        }
        $getUserData->firstname = $request->firstname;
        $getUserData->middlename = $request->middlename;
        $getUserData->lastname = $request->lastname;
        $getUserData->username = strtolower($request->username);
        $getUserData->contact_num = $request->contact_number;
        $getUserData->address = $request->address;
        $getUserData->save();
        AL::audits('patient',$base_data,$request->ip(),'Update my information.');
        return array(
            'status' => 'success',
            'message' => 'Information successfully updated',
            'url' => route('student.home.profile-settings',$base_data->username)
        );
    }

    public function editpasswordsave(Request $request){
        $base_data = Auth::guard('student')->user();
        $getStudentDetails = CF::model('Student')::find($base_data->id);

        $validator = Validator::make($request->all(),[
            'new_password' => 'required_with:confirm_password|min:8|same:confirm_password',
            'confirm_password' => 'required|min:8'
        ]);
        if(Hash::check($request->old_password,$getStudentDetails->password)){
            if($validator->fails()){
                return array(
                    'status' => 'error',
                    'messages' => $validator->errors()->first()
                );
            }elseif(Hash::check($request->new_password,$getStudentDetails->password)){
                return array(
                    'status' => 'error',
                    'messages' => 'Your new password must be different from your old password'
                );
            }else{
                $getStudentDetails->password = bcrypt($request->confirm_password);
                $getStudentDetails->save();
                AL::audits('student',$getStudentDetails,$request->ip(),'Change password via settings');
                return array(
                    'status' => 'success',
                    'message' => 'Password successfully changed',
                    'url' => route('student.home.index')
                );
            }
        }else{
            return array(
                'status' => 'error',
                'messages' => 'Your old password doesn\'t match!'
            );
        }
    }
}
