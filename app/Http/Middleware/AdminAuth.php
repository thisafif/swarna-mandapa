<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada admin_id di session dan valid di database
        $adminId = session()->get('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login')->with('error', 'Anda harus login terlebih dahulu');
        }

        $admin = Admin::find($adminId);
        if (!$admin) {
            // Hapus session jika tidak valid
            session()->forget(['admin_id', 'admin_name', 'admin_email', 'admin_authenticated']);
            return redirect()->route('admin.login')->with('error', 'Sesi admin tidak valid, silakan login lagi');
        }

        // Pastikan session menampilkan data terbaru dari DB
        session(['admin_name' => $admin->name, 'admin_email' => $admin->email, 'admin_authenticated' => true]);

        return $next($request);
    }
}
