<?php

namespace App\Modules\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;
use Exporter;

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

    public function admins(){
        return view($this->render('accounts.admin-account'));
    }

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

            $middlename = $value->middlename == null || $value->middlename == '' ? ' ' : ' '.$value->middlename.' ';
            $array[$key]['id'] = $value->id;
            $array[$key]['line_status'] = $value->account_line == 1 ? '<img src="'.URL::asset('storage/uploads/account_line/online.png').'" alt="online" class="account_line"/>  Online' : '<img src="'.URL::asset('storage/uploads/account_line/offline.png').'" alt="online" class="account_line"/> Offline';
            $array[$key]['image'] = '<img src="'.URL::asset('storage/uploads/profile_image/'.$value->image).'" alt="Profile Image" style="border-radius: 50%; width: 40px;height: 40px;"/>';
            $array[$key]['name'] = $value->firstname.$middlename.$value->lastname;
            $array[$key]['email'] = $value->email;
            $array[$key]['date_registered'] = date('M j Y',$value->date_registered);
            $array[$key]['buttons'] = "
                <button class='acc_stat btn ".$btn_class." box_shad' onclick='changeStat(".$value->id.",".$acc_stat.");'>".$btn_name."</button>
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

    public function change_acc_stat(Request $request){
        $account_status = $request->acc_stat;
        $putUpdateStatus = $account_status == 0 ? 1 : 0;
        $accountsData = CF::model($request->model)::find($request->id);
        $accountsData->account_status = $putUpdateStatus;
        $accountsData->save();
        return $putUpdateStatus;
    }

    public function librarian(Request $request){
        return view($this->render('accounts.librarian-account'));
    }

    public function students(Request $request){
        return view($this->render('accounts.student-account'));
    }

    public function getstudents(Request $request){
        
    }

    public function adminaudit(){
        return view($this->render('audits.admin-audit'));
    }

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

    public function admindownloadXlsx(Request $request){
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
        return Exporter::make('Excel')->load(collect(array_merge($mainData,$data)))->stream(date('Y-m-d-HiA-').'audit-logs-lists.xlsx');
    }

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

    public function studentaudit(){
        return view($this->render('audits.student-audit'));
    }

    public function getstudentlogs(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'admin_audits.id',
            'students.firstname',
            'students.email',
            'admin_audits.action',
            'admin_audits.ip_address',
            'admin_audits.device',
            'admin_audits.browser',
            'admin_audits.operating_system',
        ];
    }
}
