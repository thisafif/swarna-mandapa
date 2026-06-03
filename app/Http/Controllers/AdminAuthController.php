<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

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

        // Authenticate against admins table
        $admin = Admin::authenticate($request->input('email'), $request->input('password'));
        if ($admin) {
            session([
                'admin_authenticated' => true,
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_name' => $admin->name,
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
