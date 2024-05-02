<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserIsSuperadminOrOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guest()) {
            return redirect('/');
        }

        if(!Auth::user()->isSuperadmin() && !Auth::user()->isOwner()) {
            return redirect('/');
        }

        return $next($request);
    }
}
