<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $sess_user = $request->session()->get('user');
        if(empty($sess_user)) {
            return redirect('auth');
        }
        $auth_paths = ['/', '/auth'];
        if(in_array($request->path(), $auth_paths)) {
            return redirect('panel/dashboard');
        }
        return $next($request);
    }
}
