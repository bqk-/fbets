<?php

namespace App\Http\Middleware;

use Closure;
use \Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(!(Auth::check() && in_array(Auth::user()->id, \App\Http\Controllers\AdminController::$admins)))
        {
            return redirect('/');
        }

        return $next($request);
    }
}