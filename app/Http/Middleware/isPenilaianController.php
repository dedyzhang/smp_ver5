<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if (!auth()->check()) {
            return redirect('/signin');
        } else {
            if (
                auth()->user()->access === 'admin'
                || auth()->user()->access === 'kurikulum'
                || auth()->user()->access === 'kesiswaan'
                || auth()->user()->access === 'guru'
                || auth()->user()->access === 'sapras'
                || auth()->user()->access === 'kepala'
            ) {
                return $next($request);
            } else {
                abort(403);
            }
        }
    }
}
