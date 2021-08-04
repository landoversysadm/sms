<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsInstructor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(Auth::user()->role !== 3 ){
        return ($request->expectsJson())?
                response()->json(['error'=>'Unauthenticated'], 401)
                :redirect('/');
    }
        return $next($request);
    }
}
