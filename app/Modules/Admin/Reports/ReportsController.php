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
}
