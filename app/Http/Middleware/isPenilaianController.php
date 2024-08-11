<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isPenilaianController
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
                Auth::user()->access === 'admin'
                || Auth::user()->access === 'kurikulum'
                || Auth::user()->access === 'kesiswaan'
                || Auth::user()->access === 'guru'
                || Auth::user()->access === 'sapras'
                || Auth::user()->access === 'kepala'
            ) {
                return $next($request);
            } else {
                abort(403);
            }
        }
    }
}
