<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function form()
    {
        return view('booking.form');
    }

    public function storeForm(Request $request)
    {
        // Simpan data ke session untuk dipakai di halaman berikutnya
        $request->session()->put('booking', $request->all());
        return redirect()->route('booking.confirmation');
    }

    public function confirmation()
    {
        $default = [
            'check_in' => date('Y-m-d', strtotime('+1 day')),
            'check_out' => date('Y-m-d', strtotime('+3 days')),
            'guests' => 2,
            'first_name' => 'Bapak/Ibu',
            'last_name' => 'Guest',
            'phone' => '-',
            'country' => 'ID',
        ];
        $booking = array_merge($default, session('booking', []));
        return view('booking.confirmation', compact('booking'));
    }

    public function storeConfirmation(Request $request)
    {
        return redirect()->route('booking.invoice');
    }

    public function invoice()
    {
        $default = [
            'check_in' => date('Y-m-d', strtotime('+1 day')),
            'check_out' => date('Y-m-d', strtotime('+3 days')),
            'guests' => 2,
            'first_name' => 'Bapak/Ibu',
            'last_name' => 'Guest',
        ];
        $booking = array_merge($default, session('booking', []));
        return view('booking.invoice', compact('booking'));
    }

    public function status()
    {
        $default = [
            'check_in' => date('Y-m-d', strtotime('+1 day')),
            'check_out' => date('Y-m-d', strtotime('+3 days')),
            'guests' => 2,
        ];
        $booking = array_merge($default, session('booking', []));
        return view('booking.status', compact('booking'));
    }
}