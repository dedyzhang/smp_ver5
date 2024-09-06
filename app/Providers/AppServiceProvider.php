<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Orangtua;
use App\Models\Sekretaris;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer(['layouts.main'], function ($view) {
            if (Auth::check()) {
                $access = Auth::user()->access;
                if ($access == 'siswa') {
                    $account = Siswa::with('users')->where('id_login', Auth::user()->uuid)->first();
                } elseif ($access == "orangtua") {
                    $account = Orangtua::with(['users', 'siswa'])->where('id_login', Auth::user()->uuid)->first();
                } else {
                    $account = Guru::with('users')->where('id_login', Auth::user()->uuid)->first();
                }

                //...with this variable
                $view->with('account', $account);
            }
        });

        Gate::define('admin', function () {
            return Auth::user()->access === "admin";
        });
        Gate::define('kurikulum', function () {
            return Auth::user()->access === "kurikulum";
        });
        Gate::define('kesiswaan', function () {
            return Auth::user()->access === "kesiswaan";
        });
        Gate::define('sapras', function () {
            return Auth::user()->access === "sapras";
        });
        Gate::define('guru', function () {
            return Auth::user()->access === "guru";
        });
        Gate::define('siswa', function () {
            return Auth::user()->access === "siswa";
        });
        Gate::define('orangtua', function () {
            return Auth::user()->access === "orangtua";
        });
        Gate::define('kepalasekolah', function () {
            return Auth::user()->access === "kepala";
        });
        Gate::define('sekretaris', function () {
            $auth = Auth::user();
            if ($auth->access === "siswa") {
                $siswa = Siswa::where('id_login', $auth->uuid)->first();
                $sekretaris = Sekretaris::where('id_kelas', $siswa->id_kelas)->first();
                if ($sekretaris !== null) {
                    return (Auth::user()->access === "siswa" && ($sekretaris->sekretaris1 == $siswa->uuid || $sekretaris->sekretaris2 == $siswa->uuid));
                }
            }
        });
    }
}
