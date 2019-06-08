<?php

namespace App\Modules\Admin\Books;

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

class BooksController extends Controller
{
    public static $view_path = "Admin.Books";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    /* Gets the list of all books */

    public function index(){
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
        return view($this->render('index'),compact('getBooks'));
    }

    /* Add Book's Information */

    public function addbooks(){
        $getAuthors = CF::model('Author')::all();
        return view($this->render('content.add-books'),compact('getAuthors'));
    }

    /* Saves Book's Information */

    public function addbooksave(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $frontImage = $request->book_front;
        $backImage = $request->book_back;
        if($frontImage == 'undefined'){
            return array(
                'status' => 'error',
                'messages' => 'No Front Image Uploaded!'
            );
        }elseif($backImage == 'undefined'){
            return array(
                'status' => 'error',
                'messages' => 'No Back Image Uploaded!'
            );
        }else{
            DB::beginTransaction();
            try{
                $bookId = CF::model('Book')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
                $getBookId = $bookId->count() > 0 ? $bookId->get()[0]->id + 1 : 1;
                $front_extension = strtolower($frontImage->extension());
                $front_image_file = $frontImage;
                switch ($front_extension){
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        $frontImageName = 'library_front_books'.time().'.'.$front_extension;
                        break;
                    default:
                        $result['status'] = 'error';
                        $result['messages'] = 'Invalid file type for Front Book Image.';
                        return $result;
                    break;
                }
                $back_extension = strtolower($backImage->extension());
                $back_image_file = $backImage;
                switch ($back_extension){
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        $backImageName = 'library_back_books'.time().'.'.$back_extension;
                        break;
                    default:
                        $result['status'] = 'error';
                        $result['messages'] = 'Invalid file type for Front Book Image.';
                        return $result;
                    break;
                }
                $bookTitle = $request->book_title;
                $books = [
                    'added_by' => $currentLoggedId->id,
                    'front_image' => $frontImageName,
                    'back_image' => $backImageName,
                    'quantity' => $request->book_quantity,
                    'disperse' => 0,
                    'author_id' => $request->book_author,
                    'genre' => $request->book_genre,
                    'title' => $bookTitle,
                    'description' => $request->book_description,
                    'date_published' => strtotime($request->book_published),
                ];
                $result = CF::model('Book')->saveData($books, true);
                Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookId.')',$front_image_file,$frontImageName);
                Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookId.')',$back_image_file,$backImageName);
                AL::audits('admin',$currentLoggedId,$request->ip(),'Add book ('.$bookTitle.')');
                DB::commit();
                $result['url'] = route('admin.books.index');
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
    }

    /* Edit Author's Information */

    public function editbooks(Request $request,$id){
        $getAuthors = CF::model('Author')::all();
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
            ->leftjoin('authors','authors.id','books.author_id')
            ->where('books.id',$id)->get();
        return view($this->render('content.edit-books'),compact('getBooks','getAuthors'));
    }

    /* Saves the edited information of the Book. */

    public function editbooksave(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $id = $request->book_id;
        $getBookDetails = CF::model('Book')::find($id);
        $frontImage = $request->book_front;
        $backImage = $request->book_back;
        if($frontImage == 'undefined'){
            $frontimageName = $getBookDetails->front_image;
        }else{
            $frontExtension = strtolower($frontImage->extension());
            switch ($frontExtension){
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $frontimageName = 'library_front_books'.time().'.'.$frontExtension;
                    break;
                default:
                    $result['status'] = 'error';
                    $result['messages'] = 'Invalid file type for Front Book Image.';
                    return $result;
                break;
            }
            Storage::disk('uploads')->delete('uploads/book_images/book-('.$getBookDetails->id.')/'.$getBookDetails->front_image);
            Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookDetails->id.')',$frontImage,$frontimageName);
        }
        if($backImage == 'undefined'){
            $backimageName = $getBookDetails->back_image;
        }else{
            $backExtension = strtolower($backImage->extension());
            switch ($backExtension){
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $backimageName = 'library_back_books'.time().'.'.$backExtension;
                    break;
                default:
                    $result['status'] = 'error';
                    $result['messages'] = 'Invalid file type for Back Book Image.';
                    return $result;
                break;
            }
            Storage::disk('uploads')->delete('uploads/book_images/book-('.$getBookDetails->id.')/'.$getBookDetails->back_image);
            Storage::disk('uploads')->putFileAs('uploads/book_images/book-('.$getBookDetails->id.')',$backImage,$backimageName);
        }
        $bookTitle = $request->book_title;
        $getBookDetails->author_id = $request->book_author;
        $getBookDetails->front_image = $frontimageName;
        $getBookDetails->back_image = $backimageName;
        $getBookDetails->genre = $request->book_genre;
        $getBookDetails->title = $bookTitle;
        $getBookDetails->description = $request->book_description;
        $getBookDetails->date_published = strtotime($request->book_published);
        $getBookDetails->save();
        AL::audits('admin',$currentLoggedId,$request->ip(),'Edit book ('.$bookTitle.')');
        return [
            'status' => 'success',
            'messages' => 'Book Successfully Updated',
            'url' => route('admin.books.index')
        ];
    }

    /* View the Book's Information */
    
    public function viewbooks(Request $request,$id,$title){
        $getBooks = CF::model('Book')::find($id);
        return view($this->render('content.view-books'),compact('getBooks'));
    }

    /* View the list of students who borrowed books */

    public function borrowed(){
        $getBorrowedDetails = CF::model('Borrow')::all();
        return view($this->render('content.borrow-books'),compact('getBorrowedDetails'));
    }

    /* Gets the list of students who borrowed books */

    public function getborrowed(Request $request){
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
            $btn_status = $value->borrowed_status == 'approved' || $value->borrowed_status == 'returned' ? 'd-none' : '';
            $btn_approved = $value->borrowed_status == 'approved' ? '' : 'd-none';
            $array[$key]['button'] = '
                <button type="button" class="btn btn-success '.$btn_approved.' return-'.$value->id.' return-borrow" data-id="'.$value->id.'" data-url="'.route('admin.books.return-borrowed').'" data-token="'.csrf_token().'"><span class="fa fa-sign-out"></span> Mark Return</button>
                <button type="button" class="btn btn-success '.$btn_status.' approve-borrow" data-id="'.$value->id.'" data-token="'.csrf_token().'" data-url="'.route('admin.books.approved-borrowed').'"><span class="fa fa-check"></span></button>
                <button type="button" class="btn btn-danger '.$btn_status.' borrow-'.$value->id.' delete-borrow" data-id="'.$value->id.'" data-url="'.route('admin.books.delete-borrowed').'" data-token="'.csrf_token().'"><span class="fa fa-trash"></span></button>
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

    /*  Makes the borrowed status approved  */

    public function approvedborrowed(Request $request){
        $getBorrowedDetails = CF::model('Borrow')::find($request->id);
        $getBorrowedDetails->borrowed_status = 'approved';
        $getBorrowedDetails->save();
        return array(
            'status' => 'success',
            'messages' => 'Borrow details succesfully approved!',
            'url' => route('admin.books.index')
        );
    }

    /* Makes the borrowed status returned */

    public function returnborrowed(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $id = $request->id;
        $getBorrowedDetails = CF::model('Borrow')::find($id);
        $getBorrowedDetails->borrowed_status = 'returned';
        $getBorrowedDetails->save();
        AL::audits('admin',$currentLoggedId,$request->ip(),'Mark return Borrowed Detail id ('.$id.')');
        return array(
            'status' => 'success',
            'messages' => 'Borrow details succesfully returned!'
        );
    }

    /* Makes the borrowed status deleted */

    public function deleteborrowed(Request $request){
        $currentLoggedId = Auth::guard('admin')->user();
        $id = $request->id;
        $getBorrowedDetails = CF::model('Borrow')::find($id);
        $getBorrowedDetails->borrowed_status = 'deleted';
        $getBorrowedDetails->save();
        AL::audits('admin',$currentLoggedId,$request->ip(),'Delete Borrowed Detail id ('.$id.')');
        return array(
            'status' => 'success',
            'messages' => 'Borrow details succesfully deleted!'
        );
    }

    /* Render the view of Inventory */
    
    public function inventory(Request $request){
        return view($this->render('content.inventory'));
    }

    /* Get the list of books in inventory */

    public function getinventory(Request $request){
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'books.id',
            'authors.name',
            'books.title',
            'books.genre',
            'books.date_published',
            'books.quantity',
            'books.disperse',
            'books.id',
        ];
        $booksDetails = CF::model('Book')
            ->select(
                'books.id',
                'authors.name as author_name',
                'books.title',
                'books.genre',
                'books.date_published',
                'books.quantity',
                'books.disperse'
            )
            ->whereNull('books.status')
            ->leftjoin('authors','authors.id','books.author_id');

        $booksDetailsCount = $booksDetails->count();
        $booksDetails = $booksDetails->where(function($query) use ($request){
            $query
                ->orWhere('books.id','LIKE',"%".$request->search['value']."%")
                ->orWhere('authors.name','LIKE',"%".$request->search['value']."%")
                ->orWhere('books.title','LIKE',"%".$request->search['value']."%")
                ->orWhere('books.genre','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($booksDetails as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['author_name'] = $value->author_name;
            $array[$key]['book_title'] = $value->title;
            $array[$key]['genre'] = $value->genre;
            $array[$key]['date_published'] = date('M j Y',$value->date_published);
            $array[$key]['book_qty'] = $value->quantity;
            $array[$key]['book_disperse'] = $value->disperse;
            $array[$key]['button'] = '
                <button type="button" class="btn btn-success add-book-quantity" data-id="'.$value->id.'" data-title="'.$value->title.'" data-quantity="'.$value->quantity.'"><span class="fa fa-plus"></span></button>
                <button type="button" class="btn btn-danger remove-book-or-quantity" data-id="'.$value->id.'"><span class="fa fa-trash"></span></button>
            ';
        }
        $totalCount = count($array);
        $result['book_details'] = $array;
        $data = [];

        foreach($result['book_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['author_name'],
                $value['book_title'],
                $value['genre'],
                $value['date_published'],
                $value['book_qty'],
                $value['book_disperse'],
                $value['button'],
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $booksDetailsCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    /* Add quantity of Books */

    public function addquantitybooks(Request $request){
        $getBookDetails = CF::model('Book')::find($request->book_id);
        $validator = Validator::make($request->all(),[
            'quantity' => 'required|numeric'
        ]);
        if($validator->fails()){
            return array(
                'status' => 'error',
                'messages' => $validator->errors()->first()
            );
        }
        $getBookDetails->quantity = ($getBookDetails->quantity + $request->quantity);
        $getBookDetails->save();
        return [
            'status' => 'success',
            'url' => route('admin.books.inventory'),
            'message' => 'Quantity successfully added!'
        ];
    }

    /* Delete all quantity of the Books */

    public function deleteallquantity(Request $request){
        $getBookDetails = CF::model('Book')::find($request->id);
        $getBookDetails->status = 0;
        $getBookDetails->save();
        return array(
            'status' => 'success',
            'message' => 'Books are successfully deleted!'
        );
    }

    /* Disperse the quantity of books */

    public function dispersequantity(Request $request){
        $id = $request->book_row_id;
        $quantity = $request->remove_quantity;
        $getBookDetails = CF::model('Book')::find($id);
        $validator = Validator::make($request->all(),[
            'remove_quantity' => 'required|numeric'
        ]);
        if($validator->fails()){
            return array(
                'status' => 'error',
                'messages' => $validator->errors()->first()
            );
        }
        $getMinQuan = ($getBookDetails->quantity - $quantity);
        if($getMinQuan < 0){
            return array(
                'status' => 'error',
                'messages' => 'your input must be less than or equal, than the actual quantity'
            ); 
        }
        $getBookDetails->quantity = $getMinQuan;
        $getBookDetails->disperse = ($getBookDetails->disperse + $quantity);
        $getBookDetails->save();
        return array(
            'status' => 'success',
            'message' => 'Quantity successfully updated!',
            'url' => route('admin.books.inventory')
        );
    }
}
