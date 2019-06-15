<?php

namespace App\Modules\Admin\Department;

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
use Validator;

/**
 * ---------------------------------------------------------------------------
 * - DepartmentController This class controller is for Department view pages 
 * - and transactions in administrator page.
 * ---------------------------------------------------------------------------
 */

class DepartmentController extends Controller
{
    public static $view_path = "Admin.Department";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        $getDepartment = CF::model('Department')->where('department_status','!=','down')->get();
        return view($this->render('index'),compact('getDepartment'));
    }

    
    /* Gets the list of Department */

    public function getdepartment(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'id',
            'department_name',
            'created_at',
        ];
        $departmentDetails = CF::model('Department')
            ->where('department_status','!=','down');
        $departmentResultCount = $departmentDetails->count();
        $departmentDetails = $departmentDetails->where(function($query) use ($request){
            $query
                ->orWhere('id','LIKE',"%".$request->search['value']."%")
                ->orWhere('department_name','LIKE',"%".$request->search['value']."%")
                ->orWhere('created_at','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($departmentDetails as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['department_name'] = $value->department_name;
            $array[$key]['created_at'] = date('M j Y',strtotime($value->created_at));
            $array[$key]['button'] = '
                <button type="button" class="btn btn-secondary edit-department" data-id="'.$value->id.'"><span class="fa fa-edit"></span></button>
                <button type="button" class="btn btn-danger department-'.$value->id.' delete-department" data-id="'.$value->id.'" data-url="'.route('admin.department.delete-department').'" data-token="'.csrf_token().'" data-name="'.$value->department_name.'"><span class="fa fa-trash"></span></button>
            ';
        }

        $totalCount = count($array);
        $result['department_details'] = $array;
        $data = [];

        foreach($result['department_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['department_name'],
                $value['created_at'],
                $value['button'],
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $departmentResultCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    
    /* Add a Department */

    public function adddepartment(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        DB::beginTransaction();
        try{
            $departmentName = $request->departmentname;
            $department = [
                'department_name' => $departmentName,
                'department_status' => 'up'
            ];
            $result = CF::model('Department')->saveData($department, true);
            DB::commit();
            AL::audits('admin',$currentLoggedId,$request->ip(),'Add new department ('.$departmentName.')');
            $result['url'] = route('admin.department.index');
            $result['message'] = 'Successfully added department!';
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

    /* Edit Department's Information  */

    public function editdepartment(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $departmentName = $request->departmentname;
        $validator = Validator::make($request->all(),[
            'departmentname' => 'required'
        ]);
        if($validator->fails()){
            return array(
                'status' => 'error',
                'messages' => $validator->errors()->first()
            );
        }
        $validateDepartment = CF::model('Department')
            ->where('department_status','!=','down')
            ->where('department_name',$departmentName)
            ->count();
        if($validateDepartment > 0){
            return array(
                'status' => 'error',
                'message' => 'The Department name is already taken'
            );
        }
        $departmentDetails = CF::model('Department')::find($request->departmentid);
        $message = 'Edit department ('.$departmentDetails->department_name.' to '.$departmentName.')';
        AL::audits('admin',$currentLoggedId,$request->ip(),$message);
        $departmentDetails->department_name = $departmentName;
        $departmentDetails->save();
        return array(
            'status' => 'success',
            'url' => route('admin.department.index'),
            'message' => 'Department successfully Edited!'
        );
    }

    /* Delete a Department */

    public function deletedepartment(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $departmentDetails = CF::model('Department')::find($request->id);
        $departmentDetails->department_status = 'down';
        $departmentDetails->save();
        AL::audits('admin',$currentLoggedId,$request->ip(),'Delete department ('.$departmentDetails->name.')');
        return array(
            'status' => 'success',
            'messages' => 'Department successfully Deleted!'
        );
    }
}
