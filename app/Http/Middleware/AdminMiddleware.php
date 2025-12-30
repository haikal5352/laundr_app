<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek: Kalau dia LOGIN dan role-nya ADMIN, silakan lewat
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        // Kalau bukan admin, tendang balik ke dashboard biasa
        return redirect('/dashboard')->with('error', 'Anda bukan Admin!');
    }
}