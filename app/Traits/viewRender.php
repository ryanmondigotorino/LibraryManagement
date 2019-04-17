<?php

namespace App\Traits;

use ClassFactory as CF;
use Auth;


trait ViewRender {

    public function render($page) {
        
        $this->loadGlobalData();
        
        return static::$view_path.'::Views.'.$page;
    }

    public function loadGlobalData(){
      
        if(isset(request()->route()->action['guard'])){
            if (Auth::guard('admin')->check()){
                $user =  Auth::guard('admin')->user();
                \View::share('base_data', $user);
            }
            if (Auth::guard('student')->check()){
                $user =  Auth::guard('student')->user();
                \View::share('base_data', $user);
            }
        }
    }
}