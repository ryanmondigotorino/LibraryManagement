<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Department extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'department_name'
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'department_name' => [ 'required','min:3' ],
        ];

        $data['messages'] = [
            'department_name.required' => 'The Department Name is required',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Department::find($data['id']) : new Department() ;
    }
}
