<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminKurikulumKepala
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        } else {
            if (
                auth()->user()->access === 'admin' ||
                auth()->user()->access === 'kurikulum' ||
                auth()->user()->access === 'kepala'
            ) {
                return $next($request);
            } else {
                abort(403);
            }
        }
    }
}
