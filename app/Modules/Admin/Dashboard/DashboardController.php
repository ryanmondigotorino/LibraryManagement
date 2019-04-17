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

class DashboardController extends Controller
{
    public static $view_path = "Admin.Dashboard";

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
        $adminDetails = CF::model('Admin');
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

    public function students(Request $request){
        return view($this->render('accounts.student-account'));
    }

    public function getstudents(Request $request){
        
    }
}
