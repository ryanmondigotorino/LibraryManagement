<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Book extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'added_by',
        'front_image',
        'back_image',
        'author',
        'genre',
        'title',
        'description',
        'date_published',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'added_by' => [ 'required' ],
            'author' => [ 'required','min:3' ],
            'genre' => [ 'required','min:3' ],
            'title' => [ 'required','min:3' ],
            'description' => [ 'min:3' ],
            'date_published' => [ 'required' ],
        ];

        $data['messages'] = [
            'added_by.required' => 'Added By is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Book::find($data['id']) : new Book() ;
    }
}
