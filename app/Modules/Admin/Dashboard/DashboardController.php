<?php

namespace App\Modules\Admin\Dashboard;

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
use Exporter;
use Validator;
use Browser;

/**
 * ---------------------------------------------------------------------------
 * - DashboardController This class controller is for Dashboard pages, 
 * - accounts pages, audits pages and other transactions which include the
 * - admin index page, accounts management and audit pages in administrator.
 * ---------------------------------------------------------------------------
 */

class DashboardController extends Controller
{
    public static $view_path = "Admin.Dashboard";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        $base_data = Auth::guard('admin')->user();
        $getuserdata = CF::model('Admin')::find($base_data->id);
        if($base_data->account_line == 0){
            $getuserdata->account_line = 1;
            $getuserdata->save();
        }
        return view($this->render('index'));
    }

    /* Gets the status of Borrowed Books monthly */

    public function getBorrowedBooksChart(Request $request){
        $data = [];
        $currentMonthStatus = $currentborrow = 0;
        $data['statusMonthly']['Current Month']['borrow'] = 0;
        for ($m=1; $m <=12 ; $m++) {
            $month = date('Y-m', mktime(0, 0 ,0 ,$m,1,date('Y')));
            $monthName = date('M', mktime(0,0,0, $m,1,date('Y')));
            $borrow = CF::model('Borrow')
                ->select('order_number')
                ->where([
                    ['updated_at','like','%'.$month.'%']
                ])
                ->count();
            $data['statusMonthly'][$monthName]['borrow'] = $borrow;
            if(date('Y-m') == $month){
                $currentborrow = $borrow;
            }
            $data['statusMonthly']['Current Month']['borrow'] = $currentborrow;
        }
        return $data;
    }

    /* Renders the view for Admin Accounts */

    public function admins(){
        return view($this->render('accounts.admin-account'));
    }

    /* Gets the list of Admins */

    public function getadmins(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'id',
            'account_line',
            'image',
            'firstname',
            'email',
            'date_registered'
        ];
        $adminDetails = CF::model('Admin')
            ->where('account_type',$request->account_type);
        $adminResultCount = $adminDetails->count();
        $adminDetails = $adminDetails->where(function($query) use ($request){
            $query
                ->orWhere('id','LIKE',"%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(firstname,'',lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere('email','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($adminDetails as $key => $value){
            $acc_stat = $value->account_status;
            $btn_class = $acc_stat == 0 ? 'btn-success' : 'btn-danger';
            $btn_name = $acc_stat == 0 ? 'ACTIVATE' : 'DEACTIVATE';
            $adminImage = $value->image == 'noimage.png' ? URL::asset('public/css/assets/profile_image/'.$value->image) : URL::asset('storage/uploads/profile_image/'.$value->image);
            $middlename = $value->middlename == null || $value->middlename == '' ? ' ' : ' '.$value->middlename.' ';
            $array[$key]['id'] = $value->id;
            $array[$key]['line_status'] = $value->account_line == 1 ? '<img src="'.URL::asset('public/css/assets/account_line/online.png').'" alt="online" class="account_line"/>  Online' : '<img src="'.URL::asset('public/css/assets/account_line/offline.png').'" alt="online" class="account_line"/> Offline';
            $array[$key]['image'] = '<img src="'.$adminImage.'" alt="Profile Image" style="border-radius: 50%; width: 40px;height: 40px;"/>';
            $array[$key]['name'] = $value->firstname.$middlename.$value->lastname;
            $array[$key]['email'] = $value->email;
            $array[$key]['date_registered'] = date('M j Y',$value->date_registered);
            $array[$key]['buttons'] = "
            <button type='button' class='acc_stat btn ".$btn_class." box_shad change-stat' data-id='".$value->id."' data-stat='".$acc_stat."' data-model='Admin' data-url='".route('admin.dashboard.accounts.change-acc-stat')."' data-token='".csrf_token()."'>".$btn_name."</button>
            ";
        }
        $totalCount = count($array);
        $result['account_details'] = $array;
        $data = [];

        foreach($result['account_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['line_status'],
                $value['image'],
                $value['name'],
                $value['email'],
                $value['date_registered'],
                $value['buttons']
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $adminResultCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    /* Change the Account Status to Activate or Deactivate */

    public function change_acc_stat(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $account_status = $request->acc_stat;
        $putUpdateStatus = $account_status == 0 ? 1 : 0;
        $model = $request->model;
        $accountsData = CF::model($model)::find($request->id);
        $accountsData->account_status = $putUpdateStatus;
        $accountsData->save();
        if($model == 'Admin'){
            $getStatusModel = $accountsData->account_type == 'admin' ? 'Admin' : 'Librarian';
        }else{
            $getStatusModel = 'Student';
        }
        $getActualStatus = $putUpdateStatus == 0 ? 'Deactivate': 'Activate';
        AL::audits('admin',$currentLoggedId,$request->ip(),$getActualStatus.' '.$getStatusModel.' ('.$accountsData->username.')');
        return $putUpdateStatus;
    }

     /* Add Admin Accounts */

    public function addAdmins(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
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
            $username = strtolower($request->username);
            $acctype = $request->account_status;
            $admin = array(
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'image' => 'noimage.png',
                'email' => $email,
                'username' => $username,
                'password' => bcrypt($request->confirm_password),
                'account_type' => $acctype,
                'account_line' => 0,
                'account_status' => 1,
                'date_registered' => time(),
            );
            $result = CF::model('Admin')->saveData($admin, true);
            DB::commit();
            AL::audits('admin',$currentLoggedId,$request->ip(),'Add new '.$acctype.' ('.$username.')');
            $result['url'] = route('admin.dashboard.accounts.admins-account');
            $result['message'] = 'Successfully added!';
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
    }

    /* Render the view for Librarians Account Page */

    public function librarian(Request $request){
        return view($this->render('accounts.librarian-account'));
    }

    /* Render the view for Students Account Page */

    public function students(Request $request){
        $getCourse = CF::model('Course')->where('course_status','!=','down')->get();
        $getDepartment = CF::model('Department')->where('department_status','!=','down')->get();
        return view($this->render('accounts.student-account'),compact('getCourse','getDepartment'));
    }

    /* Gets the list of Students registerded */

    public function getstudents(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'students.student_num',
            'students.account_line',
            'students.image',
            'students.firstname',
            'students.email',
            'courses.name',
            'departments.department_name',
        ];
        $studentDetails = CF::model('Student')
            ->select(
                'students.id',
                'students.student_num',
                'students.account_line',
                'students.image',
                'students.firstname',
                'students.middlename',
                'students.lastname',
                'students.email',
                'students.account_status',
                'courses.name',
                'departments.department_name'
            )
            ->join('courses','courses.id','students.course_id')
            ->join('departments','departments.id','students.department_id');
        $studentResultCount = $studentDetails->count();
        $studentDetails = $studentDetails->where(function($query) use ($request){
            $query
                ->orWhere('students.student_num','LIKE',"%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,' ',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,'',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,' ',students.middlename,' ',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere('students.email','LIKE',"%".$request->search['value']."%")
                ->orWhere('courses.name','LIKE',"%".$request->search['value']."%")
                ->orWhere('departments.department_name','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($studentDetails as $key => $value){
            $acc_stat = $value->account_status;
            $btn_class = $acc_stat == 0 ? 'btn-success' : 'btn-danger';
            $btn_name = $acc_stat == 0 ? 'ACTIVATE' : 'DEACTIVATE';

            $studImages = $value->image == null || $value->image == '' ? URL::asset('public/css/assets/noimage.png') : URL::asset('storage/uploads/profile_image/user('.$value->id.')/'.$value->image);
            
            $middlename = $value->middlename == null || $value->middlename == '' ? ' ' : ' '.$value->middlename.' ';
            $array[$key]['student_num'] = $value->student_num;
            $array[$key]['line_status'] = $value->account_line == 1 ? '<img src="'.URL::asset('public/css/assets/account_line/online.png').'" alt="online" class="account_line"/>  Online' : '<img src="'.URL::asset('public/css/assets/account_line/offline.png').'" alt="online" class="account_line"/> Offline';
            $array[$key]['image'] = '<img src="'.$studImages.'" alt="Profile Image" style="border-radius: 50%; width: 40px;height: 40px;"/>';
            $array[$key]['name'] = $value->firstname.$middlename.$value->lastname;
            $array[$key]['email'] = $value->email;
            $array[$key]['course_name'] = $value->name;
            $array[$key]['department_name'] = $value->department_name;
            $array[$key]['buttons'] = "
                <button type='button' class='acc_stat btn ".$btn_class." box_shad change-stat' data-id='".$value->id."' data-stat='".$acc_stat."' data-model='Student' data-url='".route('admin.dashboard.accounts.change-acc-stat')."' data-token='".csrf_token()."'>".$btn_name."</button>
            ";
        }
        $totalCount = count($array);
        $result['account_details'] = $array;
        $data = [];

        foreach($result['account_details'] as $key => $value){
            $dataOutput = [
                $value['student_num'],
                $value['line_status'],
                $value['image'],
                $value['name'],
                $value['email'],
                $value['course_name'],
                $value['department_name'],
                $value['buttons']
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $studentResultCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    /* Add Students Account */


    public function addStudents(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
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
                'course_id' => $request->coursename,
                'department_id' => $request->departmentname,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'email' => $email,
                'username' => $username,
                'password' => bcrypt($request->confirm_password),
                'account_line' => 0,
                'account_status' => 1,
                'date_registered' => time(),
            );
            $result = CF::model('Student')->saveData($students, true);
            $result['url'] = route('admin.dashboard.accounts.students-account');
            $result['message'] = 'Successfully added!';
            DB::commit();
            AL::audits('admin',$currentLoggedId,$request->ip(),'Add new student ('.$username.')');
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
    }

    /* Render the view of Audit Rails of Admins */

    public function adminaudit(){
        return view($this->render('audits.admin-audit'));
    }

    /* Get the Audit Rails Data of Admin */

    public function getadminlogs(Request $request){
        $dateRange = $request->datePicker != null ? $request->datePicker : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));
        
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'admin_audits.id',
            'admins.account_type',
            'admins.username',
            'admin_audits.action',
            'admin_audits.ip_address',
            'admin_audits.device',
            'admin_audits.browser',
            'admin_audits.operating_system',
        ];

        $adminAuditDetails = $this->__mainQueryAdminAudit($request,$dateFrom,$dateTo);
        $adminAuditResultCount = $adminAuditDetails->count();
        $adminAuditDetails = $adminAuditDetails->where(function($query) use ($request){
            $query
                ->orWhere('admin_audits.id','LIKE',"%".$request->search['value']."%")
                ->orWhere('admins.account_type','LIKE',"%".$request->search['value']."%")
                ->orWhere('admins.email','LIKE',"%".$request->search['value']."%")
                ->orWhere('admins.username','LIKE',"%".$request->search['value']."%")
                ->orWhere('admin_audits.action','LIKE',"%".$request->search['value']."%")
                ->orWhere('admin_audits.ip_address','LIKE',"%".$request->search['value']."%")
                ->orWhere('admin_audits.device','LIKE',"%".$request->search['value']."%")
                ->orWhere('admin_audits.browser','LIKE',"%".$request->search['value']."%")
                ->orWhere('admin_audits.operating_system','LIKE',"%".$request->search['value']."%")
                ->orWhere('admin_audits.created_at','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($adminAuditDetails as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['account_type'] = $value->account_type;
            $array[$key]['username'] = '<strong>'.$value->username.'</strong>';
            $array[$key]['action'] = $value->action;
            $array[$key]['ip_address'] = $value->ip_address;
            $array[$key]['device'] = $value->device;
            $array[$key]['browser'] = $value->browser;
            $array[$key]['operating_system'] = $value->operating_system;
            $array[$key]['created_at'] = date('m-j-y h:i:A',strtotime($value->created_at));
        }

        $totalCount = count($array);
        $result['audit_details'] = $array;
        $data = [];

        foreach($result['audit_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['account_type'],
                $value['username'],
                $value['action'],
                $value['ip_address'],
                $value['device'],
                $value['browser'],
                $value['operating_system'],
                $value['created_at']
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $adminAuditResultCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    /* Download Audit Rails to Excel */

    public function admindownloadXlsx(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $dateRange = $request->date != null ? $request->date : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));

        $data[] = array(
            'Id',
            'Account Type',
            'User Name',
            'Action',
            'IP Address',
            'Device Use',
            'Browser Use',
            'Operating system use',
            'Date Created',
        );

        $adminAuditDetails = $this->__mainQueryAdminAudit($request,$dateFrom,$dateTo);
        $array = $result = [];
        foreach($adminAuditDetails->get() as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['account_type'] = $value->account_type;
            $array[$key]['username'] = $value->username;
            $array[$key]['action'] = $value->action;
            $array[$key]['ip_address'] = $value->ip_address;
            $array[$key]['device'] = $value->device;
            $array[$key]['browser'] = $value->browser;
            $array[$key]['operating_system'] = $value->operating_system;
            $array[$key]['created_at'] = date('m-j-y h:i:A',strtotime($value->created_at));
        }
        $result['audit_details'] = $array;
        foreach($result['audit_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['account_type'],
                $value['username'],
                $value['action'],
                $value['ip_address'],
                $value['device'],
                $value['browser'],
                $value['operating_system'],
                $value['created_at']
            ];
            $data[] = $dataOutput;
        };

        $mainData = array(
            array(
                'Total # of Audit Logs', count($data) - 1
            ),
            array(''),
            array(''),
        );
        AL::audits('admin',$currentLoggedId,$request->ip(),'Download the Audit Logs for Admin');
        return Exporter::make('Excel')->load(collect(array_merge($mainData,$data)))->stream(date('Y-m-d-HiA-').'audit-logs-lists.xlsx');
    }

    /* Query for Admin Audit Rails */

    public function __mainQueryAdminAudit($request,$dateFrom,$dateTo){
        $query = CF::model('Admin_audit')
            ->select(
                'admin_audits.id',
                'admins.account_type',
                'admins.email',
                'admins.username',
                'admin_audits.action',
                'admin_audits.ip_address',
                'admin_audits.device',
                'admin_audits.browser',
                'admin_audits.operating_system',
                'admin_audits.created_at'
            )
            ->join('admins','admins.id','admin_audits.admin_id')
            ->whereBetween(DB::raw('DATE(admin_audits.created_at)'),[$dateFrom,$dateTo]);
        return $query;
    }

    /* Render the view of Audit Rails of Students */

    public function studentaudit(){
        return view($this->render('audits.student-audit'));
    }

    /* Get the Audit Rails Data of Students */

    public function getstudentlogs(Request $request){
        $dateRange = $request->datePicker != null ? $request->datePicker : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));
        
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'student_audits.id',
            'students.firstname',
            'students.username',
            'student_audits.action',
            'student_audits.ip_address',
            'student_audits.device',
            'student_audits.browser',
            'student_audits.operating_system',
        ];

        $adminAuditDetails = $this->__mainQueryStudentAudit($request,$dateFrom,$dateTo);
        $adminAuditResultCount = $adminAuditDetails->count();
        $adminAuditDetails = $adminAuditDetails->where(function($query) use ($request){
            $query
                ->orWhere('student_audits.id','LIKE',"%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,' ',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,'',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,' ',students.middlename,' ',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere('students.email','LIKE',"%".$request->search['value']."%")
                ->orWhere('students.username','LIKE',"%".$request->search['value']."%")
                ->orWhere('student_audits.action','LIKE',"%".$request->search['value']."%")
                ->orWhere('student_audits.ip_address','LIKE',"%".$request->search['value']."%")
                ->orWhere('student_audits.device','LIKE',"%".$request->search['value']."%")
                ->orWhere('student_audits.browser','LIKE',"%".$request->search['value']."%")
                ->orWhere('student_audits.operating_system','LIKE',"%".$request->search['value']."%")
                ->orWhere('student_audits.created_at','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($adminAuditDetails as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['firstname'] = $value->firstname.' '.$value->lastname;
            $array[$key]['username'] = '<strong>'.$value->username.'</strong>';
            $array[$key]['action'] = $value->action;
            $array[$key]['ip_address'] = $value->ip_address;
            $array[$key]['device'] = $value->device;
            $array[$key]['browser'] = $value->browser;
            $array[$key]['operating_system'] = $value->operating_system;
            $array[$key]['created_at'] = date('m-j-y h:i:A',strtotime($value->created_at));
        }

        $totalCount = count($array);
        $result['audit_details'] = $array;
        $data = [];

        foreach($result['audit_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['firstname'],
                $value['username'],
                $value['action'],
                $value['ip_address'],
                $value['device'],
                $value['browser'],
                $value['operating_system'],
                $value['created_at']
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $adminAuditResultCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

     /* Download Audit Rails to Excel */

    public function studentdownloadXlsx(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $dateRange = $request->date != null ? $request->date : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));

        $data[] = array(
            'Id',
            'Student Name',
            'User Name',
            'Action',
            'IP Address',
            'Device Use',
            'Browser Use',
            'Operating system use',
            'Date Created',
        );

        $adminAuditDetails = $this->__mainQueryStudentAudit($request,$dateFrom,$dateTo);
        $array = $result = [];
        foreach($adminAuditDetails->get() as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['name'] = $value->firstname.' '.$value->lastname;
            $array[$key]['username'] = $value->username;
            $array[$key]['action'] = $value->action;
            $array[$key]['ip_address'] = $value->ip_address;
            $array[$key]['device'] = $value->device;
            $array[$key]['browser'] = $value->browser;
            $array[$key]['operating_system'] = $value->operating_system;
            $array[$key]['created_at'] = date('m-j-y h:i:A',strtotime($value->created_at));
        }
        $result['audit_details'] = $array;
        foreach($result['audit_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['name'],
                $value['username'],
                $value['action'],
                $value['ip_address'],
                $value['device'],
                $value['browser'],
                $value['operating_system'],
                $value['created_at']
            ];
            $data[] = $dataOutput;
        };

        $mainData = array(
            array(
                'Total # of Audit Logs', count($data) - 1
            ),
            array(''),
            array(''),
        );
        AL::audits('admin',$currentLoggedId,$request->ip(),'Download the Audit Logs for Student');
        return Exporter::make('Excel')->load(collect(array_merge($mainData,$data)))->stream(date('Y-m-d-HiA-').'audit-logs-lists.xlsx');
    }

    /* Query for Student Audit Rails */

    public function __mainQueryStudentAudit($request,$dateFrom,$dateTo){
        $query = CF::model('Student_audit')
            ->select(
                'student_audits.id',
                'students.firstname',
                'students.lastname',
                'students.email',
                'students.username',
                'student_audits.action',
                'student_audits.ip_address',
                'student_audits.device',
                'student_audits.browser',
                'student_audits.operating_system',
                'student_audits.created_at'
            )
            ->join('students','students.id','student_audits.student_id')
            ->whereBetween(DB::raw('DATE(student_audits.created_at)'),[$dateFrom,$dateTo]);
        return $query;
    }
}
