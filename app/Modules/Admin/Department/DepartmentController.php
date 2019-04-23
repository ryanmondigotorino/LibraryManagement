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
                <button class="btn btn-secondary" onclick="editdepartment('.$value->id.',\''.$value->department_name.'\');"><span class="fa fa-edit"></span></button>
                <button class="btn btn-danger department-'.$value->id.'" onclick="deletedepartment('.$value->id.',\''.$value->department_name.'\');"><span class="fa fa-trash"></span></button>
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
            return $result;
        }catch(\Exception $e){
            $errors = json_decode($e->getMessage(), true);
            $display_errors = [];
            foreach($errors as $key => $value){
                $display_errors[] = $value[0];
            }
            $result = [
                'status' => 'error',
                'message' => implode("\n",$display_errors)
            ];
            DB::rollBack();
            return $result;
        }
        Session::flash('message',$result['status']);
        return back();
    }
    public function editdepartment(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $departmentName = $request->departmentname;
        $validateDepartment = CF::model('Department')
            ->where('department_status','!=','down')
            ->where('department_name',$departmentName)
            ->count();
        if($validateDepartment > 0){
            return array(
                'status' => 'error',
                'messages' => 'The Department name is already taken'
            );
        }
        $departmentDetails = CF::model('Department')::find($request->departmentid);
        $message = 'Edit department ('.$departmentDetails->department_name.' to '.$departmentName.')';
        AL::audits('admin',$currentLoggedId,$request->ip(),$message);
        $departmentDetails->department_name = $departmentName;
        $departmentDetails->save();
        return array(
            'status' => 'success',
            'messages' => 'Department successfully Edited!'
        );
    }

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
