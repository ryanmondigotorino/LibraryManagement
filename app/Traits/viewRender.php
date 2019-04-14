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
            if(Auth::check()){
                $user =  Auth::user();
                $image = "https://cdn.iconscout.com/public/images/icon/free/png-512/avatar-user-business-man-399587fe24739d5a-512x512.png";
                \View::share('logged_user', $user);
                \View::share('profile_image', $image);
            }
        }
    }
}