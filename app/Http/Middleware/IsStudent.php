<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class IsStudent
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
        $id = Auth::user()->id;
        if(Auth::user()->role !== 1 ){
            return ($request->expectsJson())?
                    response()->json(['error'=>'Unauthenticated'], 401)
                    :redirect('/');
        }
        $student = 'App\Student'::where('user_id',$id)->first();
        if(null === $student){
            return ($request->expectsJson())?
                    response()->json(['error'=>'Unauthorized'], 403)
                    :redirect('/');
        }

        return $next($request);
    }

}
