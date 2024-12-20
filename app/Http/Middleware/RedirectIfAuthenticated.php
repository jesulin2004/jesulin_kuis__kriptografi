<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Jika tidak ada guards, defaultkan ke array dengan null
        $guards = empty($guards) ? [null] : $guards;

        // Periksa apakah pengguna sudah terautentikasi pada salah satu guard
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Arahkan ke halaman utama setelah login jika sudah terautentikasi
                return redirect(RouteServiceProvider::HOME);
            }
        }

        // Jika pengguna tidak terautentikasi, lanjutkan permintaan
        return $next($request);
    }
}
