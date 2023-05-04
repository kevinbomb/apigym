<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotPegawai
{
    public function handle($request, Closure $next, $guard = "pegawai")
    {
        if (!auth()->guard($guard)->check()) {
            return redirect(route('loginpegawai'));
        }
        return $next($request);
    }
}