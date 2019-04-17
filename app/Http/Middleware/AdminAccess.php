<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class AdminAccess {

	public function handle($request, Closure $next){
        
	    if (!Auth::guard('admin')->check()){
	    	return redirect()->route('landing.home.login');
	    }

        return $next($request);

    }

}