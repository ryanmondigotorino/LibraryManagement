<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Student extends BaseModel{
    use SoftDeletes;

    protected $fillable = [
        'student_num',
        'course_id',
        'school_id',
        'department_id',
        'firstname',
        'middlename',
        'lastname',
        'image',
        'address',
        'contact_num',
        'email',
        'username',
        'password',
        'account_line',
        'account_status',
        'date_registered',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'firstname' => [ 'required','regex:/^[a-zA-Z]+$/u','min:3' ],
            'lastname' => [ 'required','regex:/^[a-zA-Z]+$/u','min:3' ],
            'username' => [ 'required', Rule::unique('admins')->ignore($id), Rule::unique('students')->ignore($id)],
            'email' => [ 'required', 'email', Rule::unique('admins')->ignore($id), Rule::unique('students')->ignore($id)],
        ];

        $data['messages'] = [
            'username.required' => 'Username is required.',
            'username.unique' => 'This Username is already in use..',
            'email.unique' => 'This Email is already in use.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Student::find($data['id']) : new Student() ;
    }
}
