<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Department extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'department_head',
        'department_name'
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'department_name' => [ 'required','regex:/^[a-zA-Z]+$/u','min:3' ],
            'department_head' => [ 'required'],
        ];

        $data['messages'] = [
            'department_name.required' => 'The Department Name is required',
            'department_head.required' => 'The Department Head is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Department::find($data['id']) : new Department() ;
    }
}
