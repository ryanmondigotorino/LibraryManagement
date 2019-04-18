<?php

namespace App\Modules\Admin\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;

class CourseController extends Controller
{
    public static $view_path = "Admin.Course";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        return view($this->render('index'));
    }

    public function getcourses(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'id',
            'name',
            'created_at',
        ];
        $courseDetails = CF::model('Course');
        $courseResultCount = $courseDetails->count();
        $courseDetails = $courseDetails->where(function($query) use ($request){
            $query
                ->orWhere('id','LIKE',"%".$request->search['value']."%")
                ->orWhere('name','LIKE',"%".$request->search['value']."%")
                ->orWhere('created_at','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($courseDetails as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['name'] = $value->name;
            $array[$key]['created_at'] = date('M j Y',strtotime($value->created_at));
            $array[$key]['button'] = '
                <button class="btn btn-secondary" onclick="editcourse('.$value->id.',\''.$value->name.'\');"><span class="fa fa-edit"></span></button>
                <button class="btn btn-danger" onclick="deletecourse('.$value->id.',\''.$value->name.'\');"><span class="fa fa-trash"></span></button>
            ';
        }

        $totalCount = count($array);
        $result['course_details'] = $array;
        $data = [];

        foreach($result['course_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['name'],
                $value['created_at'],
                $value['button'],
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $courseResultCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    public function addcourses(Request $request){
        DB::beginTransaction();
        try{
            $course = [
                'name' => $request->coursename,
            ];
            $result = CF::model('Course')->saveData($course, true);
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
