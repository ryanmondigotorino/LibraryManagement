<?php

namespace App\Modules\Landing\Home;

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
        return view($this->render('logs.sign-up'));
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
                $result['msg'] = 'Login Successful';
            }else{
                $result['status'] = 'warning';
                $result['msg'] = 'Your account was not activated! Please check your email.';
                Auth::guard('student')->logout();
                Auth::guard('admin')->logout();
            }
        }else{
            $result['status'] = 'error';
            $result['msg'] = 'Invalid username or password!';   
        }
        return $result;
    }
    public function signupsubmit(Request $request){
        return $request->all();
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
