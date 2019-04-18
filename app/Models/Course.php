<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Course extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'name' => [ 'required','regex:/^[a-zA-Z]+$/u','min:3' ],
        ];

        $data['messages'] = [
            'name.required' => 'The Course name is required',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Course::find($data['id']) : new Course() ;
    }
}
