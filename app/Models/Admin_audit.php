<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;
/**
 * ------------------------------------------------------------------------------------------
 * -    This is the Admin_audit
 * ------------------------------------------------------------------------------------------
 */
class Admin_audit extends BaseModel{
    use SoftDeletes;
    protected $fillable = [
        'admin_id',
        'action',
        'ip_address',
        'device',
        'browser',
        'operating_system',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'admin_id' => [ 'required' ],
            'action' => [ 'required'],
        ];

        $data['messages'] = [
            'admin_id.required' => 'admin_id is required.',
            'action.required' => 'action is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Admin_audit::find($data['id']) : new Admin_audit() ;
    }
}
