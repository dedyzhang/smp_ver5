<?php

namespace App\Http\Middleware;

use App\Models\Guru;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsWalikelas
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
            $auth = Auth::user();
            $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
            if (
                $guru->walikelas !== null
            ) {
                return $next($request);
            } else {
                abort(403);
            }
        }
    }
}
