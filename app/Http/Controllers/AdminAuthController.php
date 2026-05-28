<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Hardcoded credentials (untuk sekarang)
        // TODO: Integrate dengan database User model nanti
        if ($request->input('email') === 'admin@gmail.com' && $request->input('password') === 'admin123') {
            // Simpan session
            session([
                'admin_authenticated' => true,
                'admin_email' => $request->input('email'),
                'admin_name' => 'EGA MUTIARA',
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Email atau Password salah!'])
            ->withInput();
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->flush();
        return redirect()->route('admin.login')->with('success', 'Logout berhasil!');
    }
}
