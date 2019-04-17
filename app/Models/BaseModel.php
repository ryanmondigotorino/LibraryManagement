<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Log;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BaseModel extends Authenticatable{

    public static function saveData($data, $validate = false){
     
        try {
            
            if($validate == true){        
                
                $validation = static::rules($data);
            
                $validator = Validator::make($data, $validation['rules'], $validation['messages']);

                if($validator->fails()){

                    DB::rollBack();
                    
                    Log::error('Validation Failed');
                    throw new \Exception($validator->messages());
                    
                }

            }
          
            
            $query = static::getInstance($data);
            $query->fill($data);
            $query->save();
           
            if($query->save()){

                return array(
                    'id'        => $query->id,
                    'status'    => 'success',
                    'messages'  => 'Saved Successfully!',
                    'code'      => 200
                );

            }else{

                return array(
                    'id'        => 0,
                    'status'    => 'error',
                    't'         => 'An Error has occured!',
                    'messages'  => 'Please contact Lasedia Furniture support. 1',
                    'code'      => 400
                );

            }

        }catch(\Illuminate\Database\QueryException $e) {

            return ( array(
                'id'        => 0,
                'status'    => 'error',
                't'         => 'An Error has occured!',
                'messages'=> $e->getMessage(),
                'code'      => 400
            ));

        }

    }

}