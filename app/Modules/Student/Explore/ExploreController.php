<?php

namespace App\Modules\Student\Explore;

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

class ExploreController extends Controller
{
    public static $view_path = "Student.Explore";

    public function index(Request $request){
        if($request->search){
            $searched = $request->search;
            $getBooks = CF::model('Book')
                ->select(
                    'books.id',
                    'books.front_image',
                    'books.back_image',
                    'books.genre',
                    'books.title',
                    'books.description',
                    'books.date_published',
                    'books.created_at',
                    'authors.name as author_name',
                    'authors.image as author_image',
                    'authors.email as author_email',
                    'authors.favorite_quote as author_quote'
                )
                ->whereNull('books.status')
                ->leftjoin('authors','authors.id','books.author_id')
                ->where(function($query) use ($request,$searched){
                    $query->orWhere('books.genre','like',"%".$searched."%");
                    $query->orWhere('books.title','like',"%".$searched."%");
                    $query->orWhere('books.description','like',"%".$searched."%");
                    $query->orWhere('authors.email','like',"%".$searched."%");
                    $query->orWhere('authors.name','like',"%".$searched."%");
                });
            $placeholder = $searched;
        }else{
            $getBooks = CF::model('Book')
                ->select(
                    'books.id',
                    'books.front_image',
                    'books.back_image',
                    'books.genre',
                    'books.title',
                    'books.description',
                    'books.date_published',
                    'books.created_at',
                    'authors.name as author_name',
                    'authors.image as author_image',
                    'authors.email as author_email',
                    'authors.favorite_quote as author_quote'
                )
                ->whereNull('books.status')
                ->leftjoin('authors','authors.id','books.author_id');
            $placeholder = '';
        }
        if($request->search_category){
            $placeholderCategory = $request->search_category;
            $getBooks = CF::model('Book')
                ->select(
                    'books.id',
                    'books.front_image',
                    'books.back_image',
                    'books.genre',
                    'books.title',
                    'books.description',
                    'books.date_published',
                    'books.created_at',
                    'authors.name as author_name',
                    'authors.image as author_image',
                    'authors.email as author_email',
                    'authors.favorite_quote as author_quote'
                )
                ->whereNull('books.status')
                ->leftjoin('authors','authors.id','books.author_id')
                ->where(function($query) use ($request,$placeholderCategory){
                    $query->orWhere('books.genre','like',"%".$placeholderCategory."%");
                });
        }else{
            $placeholderCategory = 'Suggested for you';
        }
        return view($this->render('index'),
            compact(
                'getBooks',
                'placeholder',
                'placeholderCategory'
            ));
    }
    public function viewbook(Request $request,$id){
        $getBooks = CF::model('Book')
            ->select(
                'books.id',
                'books.front_image',
                'books.back_image',
                'books.genre',
                'books.title',
                'books.description',
                'books.date_published',
                'books.created_at',
                'authors.id as author_id',
                'authors.name as author_name',
                'authors.image as author_image',
                'authors.email as author_email',
                'authors.favorite_quote as author_quote'
            )
            ->whereNull('books.status')
            ->leftjoin('authors','authors.id','books.author_id')
            ->where('books.id',$id)
            ->get();
        return view($this->render('content.view-book'),compact('getBooks'));
    }

    public function borrowedBooks(Request $request){
        $getBorrowedDetails = CF::model('Borrow')::all();
        return view($this->render('content.borrowed-book'),compact('getBorrowedDetails'));
    }

    public function getBorrowedBooks(Request $request){
        $currentLoggedId = Auth::guard('student')->user();
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
            ->where('borrows.borrowed_status','!=','deleted');
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
            $btn_pending = $value->borrowed_status == 'approved' || $value->borrowed_status == 'returned' ? 'd-none' : '';
            $btn_renew = $value->borrowed_status == 'approved'? '' : 'd-none';
            $array[$key]['button'] = '
                <button type="button" class="btn btn-success '.$btn_renew.' renew-'.$value->id.' renew-borrow" data-id="'.$value->id.'"><span class="fa fa-plus"></span> Renew Borrow</button>
                <button type="button" class="btn btn-danger '.$btn_pending.' borrow-'.$value->id.' delete-borrow" data-id="'.$value->id.'" data-token="'.csrf_token().'" data-url="'.route("student.explore.borrowed-books-cancel").'"><span class="fa fa-remove"></span> Cancel Borrow</button>
            ';
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
                $value['button'],
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

    public function borrowedBooksSave(Request $request){
        DB::beginTransaction();
        try{
            $currentLoggedId = Auth::guard('student')->user();
            $id = $request->book_id;
            $validateBook = CF::model('Borrow')->where('id',$id);
            if($validateBook->count() > 0){
                $validate = $validateBook->get();
                if($validate[0]->borrowed_status == "approved" || $validate[0]->borrowed_status == "pending"){
                    return [
                        'status' => 'error',
                        'messages' => 'You already borrowed this book.'
                    ];
                }
            }
            $getQuantity = 1;
            $putId = '{"'.$id.'":'.$getQuantity.'}';
            $borrowed_books = array(
                'student_id' => $currentLoggedId->id,
                'books' => $putId,
                'return_in' => strtotime("+5 weekday",time()),
                'borrowed_status' => 'pending'
            );
            $result = CF::model('Borrow')->saveData($borrowed_books, true);
            $result['url'] = route('student.explore.borrowed-books');
            AL::audits('student',$currentLoggedId,$request->ip(),'Borrow Book');
            DB::commit();
            return $result;
        }catch(\Exception $e){
            return $e;
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

    public function borrowedBooksCancel(Request $request){
        $currentLoggedId = Auth::guard('student')->user();
        $id = $request->id;
        $getBorrowedDetails = CF::model('Borrow')::find($id);
        $getBorrowedDetails->borrowed_status = 'deleted';
        $getBorrowedDetails->save();
        AL::audits('student',$currentLoggedId,$request->ip(),'Cancel Borrowed Detail id ('.$id.')');
        return array(
            'status' => 'success',
            'messages' => 'Borrow details succesfully canceled!'
        );
    }

    public function borrowedBooksRenew(Request $request){
        $days = $request->days_extend;
        $currentLoggedId = Auth::guard('student')->user();
        $id = $request->id;
        $getBorrowedDetails = CF::model('Borrow')::find($id);
        $getBorrowedDetails->borrowed_status = 'pending';
        $getBorrowedDetails->return_in = strtotime("+".$days." weekdays",$getBorrowedDetails->return_in);
        $getBorrowedDetails->save();
        AL::audits('student',$currentLoggedId,$request->ip(),'Renew Borrowed Detail id ('.$id.')');
        return array(
            'status' => 'success',
            'messages' => 'Borrow details succesfully canceled!'
        );
    }
}
