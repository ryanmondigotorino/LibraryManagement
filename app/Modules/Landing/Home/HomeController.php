<?php

namespace App\Modules\Landing\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use ClassFactory as CF;
use AuditLogs as AL;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendEmailVerification;

use Auth;
use View;
use DB;
use URL;
use Browser;
use Validator;

class HomeController extends Controller
{
    public static $view_path = "Landing.Home";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        if(Auth::guard('student')->check()){
            return redirect()->route('student.home.index');
        }if(Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard.index');
        }
        return view($this->render('index'));
    }
    public function signup(Request $request){
        if(Auth::guard('student')->check()){
            return redirect()->route('student.home.index');
        }if(Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard.index');
        }
        $getDepartments = CF::model('Department')
            ->where('department_status','up')
            ->get();
        $getCourses = CF::model('Course')
            ->where('course_status','up')
            ->get();
        return view($this->render('logs.sign-up'),compact('getDepartments','getCourses'));
    }
    public function login(Request $request){
        if(Auth::guard('student')->check()){
            return redirect()->route('student.home.index');
        }if(Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard.index');
        }
        return view($this->render('logs.login'));
    }
    public function loginsubmit(Request $request){
        $field = filter_var($request->email_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $studentguard = Auth::guard('student')->attempt([$field => $request->email_username,'password' => $request->password]);
        $adminGuard = Auth::guard('admin')->attempt([$field => $request->email_username,'password' => $request->password]);
        $student = Auth::guard('student')->attempt([$field => $request->email_username,'password' => $request->password,'account_status' => 1]);
        $admin = Auth::guard('admin')->attempt([$field => $request->email_username,'password' => $request->password,'account_status' => 1]);
        if($studentguard || $adminGuard){
            if($student || $admin){
                if(Auth::guard('admin')->check()){
                    $user = Auth::guard('admin')->user();
                    AL::audits('admin',$user,$request->ip(),'Logged-in');
                }elseif(Auth::guard('student')->check()){
                    $user = Auth::guard('student')->user();
                    AL::audits('student',$user,$request->ip(),'Logged-in');
                }
                $result['status'] = 'success';
                $result['url'] = 'none';
                $result['message'] = 'Login Successful';
            }else{
                $result['status'] = 'warning';
                $result['message'] = 'Your account was not activated! Please check your email.';
                Auth::guard('student')->logout();
                Auth::guard('admin')->logout();
            }
        }else{
            $result['status'] = 'error';
            $result['message'] = 'Invalid username or password!';   
        }
        return $result;
    }
    public function signupsubmit(Request $request){
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(),[
                'password' => 'required_with:confirm_password|min:8|same:confirm_password',
                'confirm_password' => 'required|min:8'
            ]);
            if($validator->fails()){
                return array(
                    'status' => 'error',
                    'messages' => $validator->errors()->first()
                );
            }
            $email = strtolower($request->email);
            $studentnum = CF::model('Student')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
            $getStudentNumber = $studentnum->count() > 0 ? $studentnum->get()[0]->id + 1 : 1;
            $username = strtolower($request->username);
            $students = array(
                'student_num' => date('Y').str_pad($getStudentNumber, 5, '0', STR_PAD_LEFT),
                'course_id' => $request->course,
                'department_id' => $request->department,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'image' => 'noimage.png',
                'email' => $email,
                'username' => $username,
                'password' => bcrypt($request->confirm_password),
                'account_line' => 0,
                'account_status' => 0,
                'date_registered' => time(),
            );
            $result = CF::model('Student')->saveData($students, true);
            DB::commit();
            $data = array(
                'name' => $request->firstname.' '.$request->lastname,
                'username' => $username,
                'email' => $email
            );
            Mail::to($email)->send(new SendEmailVerification($data));
            $result['status'] = 'success';
            $result['url'] = route('landing.home.login');
            $result['message'] = 'Signup Successful. Please check your email for account verification.';
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
    public function accountVerification($userName){
        $customerDetails = CF::model('Student')->where('username',$userName)->get();
        $customerDetails[0]->account_status = 1;
        $customerDetails[0]->save();
        return redirect()->route('landing.home.login');
    }
    public function logout(Request $request){
        $guard = $request->guard;
        if(Auth::guard($guard)->check()){
            $accountsData = CF::model($request->model)::find($request->id);
            AL::audits($guard,$accountsData,$request->ip(),'Logged-out');
            $accountsData->account_line = 0;
            $accountsData->save();
            Auth::guard($guard)->logout();
            return 'success';
        }
    }
}
