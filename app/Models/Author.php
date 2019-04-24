<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Author extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'email',
        'favorite_quote'
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'name' => [ 'required','min:3',Rule::unique('authors')->ignore($id), ],
        ];

        $data['messages'] = [
            'name.required' => 'The Author name is required',
            'name.unique' => 'The Author name is already existed',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Author::find($data['id']) : new Author() ;
    }
}
