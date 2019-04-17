<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class StudentAccess {

	public function handle($request, Closure $next){
        
	    if (!Auth::guard('student')->check()){
	    	return redirect()->route('landing.home.login');
	    }

        return $next($request);

    }

}