<?php

namespace App\Modules\Admin\Department;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
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
        return view($this->render('index'));
    }

    public function getdepartment(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'id',
            'department_name',
            'created_at',
        ];
        $departmentDetails = CF::model('Department');
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
                <button class="btn btn-danger" onclick="deletedepartment('.$value->id.',\''.$value->department_name.'\');"><span class="fa fa-trash"></span></button>
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
        DB::beginTransaction();
        try{
            $department = [
                'department_name' => $request->departmentname,
            ];
            $result = CF::model('Department')->saveData($department, true);
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
                'message' => implode("\n",$display_errors)
            ];
            DB::rollBack();
            return $result;
        }
        Session::flash('message',$result['status']);
        return back();
    }
}
