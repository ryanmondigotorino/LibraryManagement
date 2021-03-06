<?php

namespace App\Modules\Admin\Course;

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
use Validator;

/**
 * ---------------------------------------------------------------------------
 * - CourseController This class controller is for Course view pages and transactions 
 * - in administrator page.
 * ---------------------------------------------------------------------------
 */

class CourseController extends Controller
{
    public static $view_path = "Admin.Course";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        $getCourse = CF::model('Course')->where('course_status','!=','down')->get();
        return view($this->render('index'),compact('getCourse'));
    }

    /* Gets the list of Courses */

    public function getcourses(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'id',
            'name',
            'created_at',
        ];
        $courseDetails = CF::model('Course')
            ->where('course_status','!=','down');
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
                <button type="button" class="btn btn-secondary edit-course-btn" data-id="'.$value->id.'" data-name="'.$value->name.'"><span class="fa fa-edit"></span></button>
                <button type="button" class="btn btn-danger course-'.$value->id.' delete-course-btn" data-url="'.route('admin.course.delete-courses').'" data-token="'.csrf_token().'" data-id="'.$value->id.'" data-name="'.$value->name.'"><span class="fa fa-trash"></span></button>
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
    
    /* Add a Course */

    public function addcourses(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        DB::beginTransaction();
        try{
            $courseName = $request->coursename;
            $course = [
                'name' => $courseName,
                'course_status' => 'up'
            ];
            $result = CF::model('Course')->saveData($course, true);
            DB::commit();
            AL::audits('admin',$currentLoggedId,$request->ip(),'Add course ('.$courseName.')');
            $result['url'] = route('admin.course.index');
            $result['message'] = 'Successfully added course!';
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

    /* Edit the Course Information */

    public function editcourses(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $courseName = $request->coursename;
        $validator = Validator::make($request->all(),[
            'coursename' => 'required'
        ]);
        if($validator->fails()){
            return array(
                'status' => 'error',
                'messages' => $validator->errors()->first()
            );
        }
        $validateCourse = CF::model('Course')
            ->where('course_status','!=','down')
            ->where('name',$courseName)
            ->count();
        if($validateCourse > 0){
            return array(
                'status' => 'error',
                'messages' => 'The Course name is already taken'
            );
        }
        $courseDetails = CF::model('Course')::find($request->courseid);
        AL::audits('admin',$currentLoggedId,$request->ip(),'Edit course ('.$courseDetails->name.' to '.$courseName.')');
        $courseDetails->name = $courseName;
        $courseDetails->save();
        return array(
            'status' => 'success',
            'message' => 'Course successfully Edited!',
            'url' => route('admin.course.index')
        );
    }

    /* Delete the Course */

    public function deletecourses(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $courseDetails = CF::model('Course')::find($request->id);
        $courseDetails->course_status = 'down';
        $courseDetails->save();
        AL::audits('admin',$currentLoggedId,$request->ip(),'Delete course ('.$courseDetails->name.')');
        return array(
            'status' => 'success',
            'messages' => 'Course successfully Deleted!'
        );
    }
}
