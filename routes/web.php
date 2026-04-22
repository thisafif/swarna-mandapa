<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/',              [BookingController::class, 'form'])               ->name('form');
    Route::post('/',             [BookingController::class, 'storeForm'])          ->name('store');
    Route::get('/confirmation',  [BookingController::class, 'confirmation'])       ->name('confirmation');
    Route::post('/confirmation', [BookingController::class, 'storeConfirmation']) ->name('confirmation.store');
    Route::get('/invoice',       [BookingController::class, 'invoice'])            ->name('invoice');
    Route::get('/status',        [BookingController::class, 'status'])             ->name('status');
});

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function (\Illuminate\Http\Request $request) {
    if ($request->input('email') === 'admin@gmail.com' && $request->input('password') === 'admin123') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->back()->withErrors(['email' => 'Email atau Password salah!'])->withInput();
})->name('admin.login.submit');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/edit-profile', function () {
    return view('admin.edit_profile');
})->name('admin.edit_profile');

Route::post('/admin/edit-profile', function (\Illuminate\Http\Request $request) {
    session([
        'admin_name' => $request->input('name', 'EGA MUTIARA'),
        'admin_email' => $request->input('email', 'admin@gmail.com')
    ]);
    // Flash message parameter so dashboard can show it
    return redirect()->route('admin.dashboard')->with('profile_updated', true);
})->name('admin.edit_profile.submit');

Route::get('/admin/manual-booking', function () {
    return view('admin.manual_booking');
})->name('admin.manual_booking');

Route::get('/admin/booking-list', function () {
    return view('admin.booking_list');
})->name('admin.booking_list');

Route::get('/admin/availability-calendar', function () {
    return view('admin.calendar');
})->name('admin.calendar');

Route::get('/admin/villa-settings', function () {
    return view('admin.villa_settings');
})->name('admin.villa_settings');

Route::post('/admin/villa-settings', function (\Illuminate\Http\Request $request) {
    $promoName = $request->input('promo_name', 'Configuration');
    return redirect()->back()->with('success', "{$promoName} config has been successfully saved!");
});