<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Course extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'name' => [ 'required','min:3' ],
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
