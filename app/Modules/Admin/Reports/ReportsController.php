<?php

namespace App\Modules\Admin\Reports;

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
use AuditLogs as AL;

class ReportsController extends Controller
{
    public static $view_path = "Admin.Reports";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        return view($this->render('index'));
    }

    public function getReturnedBooksChart(Request $request){
        $data = [];
        $currentMonthStatus = $currentborrow = 0;
        $data['statusMonthly']['Current Month']['borrow'] = 0;
        for ($m=1; $m <=12 ; $m++) {
            $month = date('Y-m', mktime(0, 0 ,0 ,$m,1,date('Y')));
            $monthName = date('M', mktime(0,0,0, $m,1,date('Y')));
            $borrow = CF::model('Borrow')
                ->select('order_number')
                ->where([
                    ['borrowed_status','returned'],
                    ['updated_at','like','%'.$month.'%'],
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

    public function getReturnBooks(Request $request){
        $dateRange = $request->datePicker != null ? $request->datePicker : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));

        $start = $request->start;
        $length = $request->length;
        $columns = [
            'borrows.id',
            'students.student_num',
            'students.lastname',
            'borrows.books',
            'borrows.return_in',
            'borrows.penalty',
            'borrows.borrowed_status',
        ];
        $borrowedDetails = CF::model('Borrow')
            ->select(
                'borrows.id',
                'students.student_num',
                'students.firstname',
                'students.middlename',
                'students.lastname',
                'borrows.books',
                'borrows.return_in',
                'borrows.penalty',
                'borrows.borrowed_status'
            )
            ->join('students','students.id','borrows.student_id')
            ->where('borrows.borrowed_status','returned')
            ->whereBetween(DB::raw('DATE(borrows.created_at)'),[$dateFrom,$dateTo]);
        $borrowedDetailsCount = $borrowedDetails->count();
        $borrowedDetails = $borrowedDetails->where(function($query) use ($request){
            $query
                ->orWhere('borrows.id','LIKE',"%".$request->search['value']."%")
                ->orWhere('students.student_num','LIKE',"%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,' ',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,'',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(students.firstname,' ',students.middlename,' ',students.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere('borrows.penalty','LIKE',"%".$request->search['value']."%")
                ->orWhere('borrows.borrowed_status','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($borrowedDetails as $key => $value){
            $books_id = json_decode($value->books);
            $book = [];
            foreach($books_id as $keyBook => $bookVal){
                $getBooks = CF::model('Book')::find($keyBook);
                $book['title'] = $getBooks->title;
            }
            $middlename = $value->middlename == null || $value->middlename == '' ? ' ' : ' '.$value->middlename.' ';
            $array[$key]['id'] = $value->id;
            $array[$key]['student_num'] = $value->student_num;
            $array[$key]['student_name'] = $value->firstname.$middlename.$value->lastname;
            $array[$key]['books'] = $book['title'];
            $array[$key]['date_return'] = date('M j Y',$value->return_in);
            $array[$key]['penalty'] = $value->penalty == null || $value->penalty == '' ? 'Penalty not set.' : 'P. '.number_format($value->penalty,2).' pesos';
            $array[$key]['borrowed_status'] = $value->borrowed_status;
            $btn_status = $value->borrowed_status == 'approved' || $value->borrowed_status == 'returned' ? 'd-none' : '';
        }
        $totalCount = count($array);
        $result['borrowed_details'] = $array;
        $data = [];

        foreach($result['borrowed_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['student_num'],
                $value['student_name'],
                $value['books'],
                $value['date_return'],
                $value['penalty'],
                $value['borrowed_status'],
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $borrowedDetailsCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    public function downloadxlsx(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $dateRange = $request->date != null ? $request->date : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));

        $data[] = array(
            'Id',
            'Student Number',
            'Student Name',
            'Book Title',
            'Date Return',
            'Penalty',
            'Status',
        );

        $borrowedDetails = CF::model('Borrow')
            ->select(
                'borrows.id',
                'students.student_num',
                'students.firstname',
                'students.middlename',
                'students.lastname',
                'borrows.books',
                'borrows.return_in',
                'borrows.penalty',
                'borrows.borrowed_status'
            )
            ->join('students','students.id','borrows.student_id')
            ->where('borrows.borrowed_status','returned')
            ->whereBetween(DB::raw('DATE(borrows.created_at)'),[$dateFrom,$dateTo]);
        $array = $result = [];

        foreach($borrowedDetails->get() as $key => $value){
            $books_id = json_decode($value->books);
            $book = [];
            foreach($books_id as $keyBook => $bookVal){
                $getBooks = CF::model('Book')::find($keyBook);
                $book['title'] = $getBooks->title;
            }
            $array[$key]['id'] = $value->id;
            $array[$key]['student_num'] = $value->student_num;
            $array[$key]['name'] = $value->firstname.' '.$value->middlename.' '.$value->lastname;
            $array[$key]['books'] = $book['title'];
            $array[$key]['return_in'] = date('M j Y',$value->return_in);
            $array[$key]['penalty'] = 'P. '.number_format($value->penalty,2);
            $array[$key]['borrowed_status'] = $value->borrowed_status;
        }
        $result['audit_details'] = $array;
        foreach($result['audit_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['student_num'],
                $value['name'],
                $value['books'],
                $value['return_in'],
                $value['penalty'],
                $value['borrowed_status'],
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
}
