<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah admin sudah login (ada di session)
        if (!session()->has('admin_authenticated') || !session()->get('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Anda harus login terlebih dahulu');
        }

        return $next($request);
    }
}
