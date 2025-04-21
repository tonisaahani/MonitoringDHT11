<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login dan memiliki role admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request); // izinkan lanjut
        }

        // Jika bukan admin, redirect atau abort
        return abort(403, 'Unauthorized access.');
    }
}
