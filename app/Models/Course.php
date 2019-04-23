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
        'course_status',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'name' => [ 'required','min:3',Rule::unique('courses')->ignore($id), ],
        ];

        $data['messages'] = [
            'name.required' => 'The Course name is required',
            'name.unique' => 'The Course name is already taken',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Course::find($data['id']) : new Course() ;
    }
}
