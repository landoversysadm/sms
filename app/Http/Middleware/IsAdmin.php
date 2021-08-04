<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdmin
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
        if(Auth::user()->role === 2  || Auth::user()->role === 4){
          return $next($request);
        }
        return ($request->expectsJson())?
          response()->json(['error'=>'Unauthenticated'], 401)
          :redirect('/');
    }
}
