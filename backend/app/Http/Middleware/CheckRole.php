<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
    // Cek apakah user sudah login dan apakah role-nya ada di daftar yang diizinkan
    if (auth()->check() && in_array(auth()->user()->role, $roles)) {
        return $next($request);
    }

    // Jika tidak punya akses, lempar ke halaman depan atau error
    return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
