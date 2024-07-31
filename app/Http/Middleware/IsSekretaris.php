<?php

namespace App\Http\Middleware;

use App\Models\Sekretaris;
use App\Models\Siswa;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSekretaris
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
            $auth = auth()->user();
            $siswa = Siswa::where('id_login', $auth->uuid)->first();
            $sekretaris = Sekretaris::where('id_kelas', $siswa->id_kelas)->first();
            if (
                $sekretaris !== null
            ) {
                if ($sekretaris->sekretaris1 == $siswa->uuid || $sekretaris->sekretaris2 == $siswa->uuid) {
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
