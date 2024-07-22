<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isNgajar
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
                auth()->user()->access === 'kurikulum'
                || auth()->user()->access === 'guru'
                || auth()->user()->access === 'kesiswaan'
                || auth()->user()->access === 'sapras'
            ) {
                return $next($request);
            } else {
                abort(403);
            }
        }
        // return $next($request);
    }
}
