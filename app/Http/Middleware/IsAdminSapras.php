<?php

namespace App\Http\Middleware;

use App\Models\Guru;
use App\Models\RuangKelas;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminSapras
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
                Auth::user()->access === 'admin' ||
                Auth::user()->access === 'sapras'
            ) {
                return $next($request);
            } else {
                $auth = Auth::user();
                $guru = Guru::with('walikelas')->where('id_login', $auth->uuid)->first();
                if (
                    $guru->walikelas !== null
                ) {
                    $ruang = RuangKelas::where('id_kelas', $guru->walikelas->id_kelas)->first();

                    if ($ruang !== null) {
                        return $next($request);
                    } else {
                        abort(403);
                    }
                } else {
                    abort(403);
                }
            }
        }
    }
}
