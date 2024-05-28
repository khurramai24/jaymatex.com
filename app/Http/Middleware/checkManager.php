<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class checkManager
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
        $isAuth = Session::get('manager');
        if(!$isAuth){
            return redirect('/manager/login');
        }
        View::share('privilege', $isAuth);
        return $next($request);
    }
}
