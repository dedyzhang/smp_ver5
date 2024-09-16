<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsSiswaOrangtua
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/signin');
        } else {
            if (
                Auth::user()->access === 'siswa' ||
                Auth::user()->access === 'orangtua'
            ) {
                return $next($request);
            } else {
                abort(403);
            }
        }
    }
}
