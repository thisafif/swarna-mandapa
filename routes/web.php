<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Models\Admin;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('index');
});

Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact-us');

Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');

// ─── Reviews (PUBLIC) ────────────────────────────────────────
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'form'])->name('form');
    Route::post('/', [BookingController::class, 'storeForm'])->name('store');
    Route::get('/confirmation', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::post('/confirmation', [BookingController::class, 'storeConfirmation'])->name('confirmation.store');
    Route::get('/invoice', [BookingController::class, 'invoice'])->name('invoice');
    Route::get('/invoice/pdf', [BookingController::class, 'invoicePdf'])->name('invoice.pdf');
    Route::get('/status', [BookingController::class, 'status'])->name('status');
    Route::get('/pending', [BookingController::class, 'pending'])->name('pending');
});

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function (\Illuminate\Http\Request $request) {
    $admin = Admin::authenticate($request->input('email'), $request->input('password'));

    if ($admin) {
        session(['admin_id' => $admin->id, 'admin_name' => $admin->name, 'admin_email' => $admin->email]);
        return redirect()->route('admin.dashboard');
    }

    return redirect()->back()->withErrors(['email' => 'Email atau Password salah!'])->withInput();
})->name('admin.login.submit');

Route::post('/admin/logout', function (Request $request) {
    $request->session()->forget(['admin_id', 'admin_name', 'admin_email']);
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/edit-profile', function () {
    return view('admin.edit_profile');
})->name('admin.edit_profile');

Route::post('/admin/edit-profile', function (\Illuminate\Http\Request $request) {
    session([
        'admin_name'  => $request->input('name', 'EGA MUTIARA'),
        'admin_email' => $request->input('email', 'admin@gmail.com'),
    ]);

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

// ─── Reviews (ADMIN) ─────────────────────────────────────────
Route::get('/admin/reviews', [ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
Route::patch('/admin/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
Route::patch('/admin/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('admin.reviews.reject');
Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

// ─── API ─────────────────────────────────────────────────────
Route::get('/api/unavailable-dates', [BookingController::class, 'unavailableDates'])->name('booking.unavailable');
Route::get('/api/booking-status/{code}', [BookingController::class, 'bookingStatus'])->name('api.booking.status');

use App\Http\Controllers\PaymentController;

Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::post('/payment/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/payment/return', [PaymentController::class, 'returnPage'])->name('payment.return');