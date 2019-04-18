<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Student_audit extends BaseModel{
    use SoftDeletes;
    protected $fillable = [
        'student_id',
        'action',
        'ip_address',
        'device',
        'browser',
        'operating_system',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'student_id' => [ 'required' ],
            'action' => [ 'required'],
        ];

        $data['messages'] = [
            'student_id.required' => 'student_id is required.',
            'action.required' => 'action is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Student_audit::find($data['id']) : new Student_audit() ;
    }
}
