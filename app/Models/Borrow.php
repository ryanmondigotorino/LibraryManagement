<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Borrow extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'books',
        'return_in',
        'penalty',
        'borrowed_status'
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'books' => [ 'required' ],
        ];

        $data['messages'] = [
            'books.required' => 'The Books id is required',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Borrow::find($data['id']) : new Borrow() ;
    }
}
