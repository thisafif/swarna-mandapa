<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;

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
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store'); // ← tambah ini

Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'form'])->name('form');
    Route::post('/', [BookingController::class, 'storeForm'])->name('store');
    Route::get('/confirmation', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::post('/confirmation', [BookingController::class, 'storeConfirmation'])->name('confirmation.store');
    Route::get('/invoice', [BookingController::class, 'invoice'])->name('invoice');
    Route::get('/status', [BookingController::class, 'status'])->name('status');
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

// Base Price Update
Route::post('/admin/villa-settings/base-price', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'base_price' => 'required|numeric|min:0',
    ]);

    \App\Models\VillaPrice::updateOrCreate(
        ['is_active' => true],
        [
            'price_per_night' => $request->base_price,
            'valid_from'      => now()->toDateString(),
            'valid_until'     => null,
            'is_active'       => true,
        ]
    );

    return redirect()->route('admin.villa_settings')->with('success', 'Base price updated successfully!');
})->name('admin.villa_settings.base_price');

// Promo Update
Route::post('/admin/villa-settings', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'promo_name'       => 'required|string|max:100',
        'promo_code'       => 'required|string|max:50',
        'discount_percent' => 'required|numeric|min:1|max:100',
        'valid_from'       => 'required|date',
        'valid_until'      => 'required|date|after_or_equal:valid_from',
        'promo_status'     => 'required|in:active,inactive',
    ]);

    \App\Models\Promo::updateOrCreate(
        ['code' => strtoupper($request->promo_code)],
        [
            'name'             => $request->promo_name,
            'discount_percent' => $request->discount_percent,
            'valid_from'       => $request->valid_from,
            'valid_until'      => $request->valid_until,
            'is_active'        => $request->promo_status === 'active',
        ]
    );

    return redirect()->back()->with('success', "Promo '{$request->promo_name}' berhasil disimpan!");
})->name('admin.villa_settings.save');

// ─── Promo Management ─────────────────────────────────────────
use App\Http\Controllers\PromoController;
Route::get('/admin/promo/{promo}/edit', [PromoController::class, 'edit'])->name('admin.promo.edit');
Route::put('/admin/promo/{promo}', [PromoController::class, 'update'])->name('admin.promo.update');
Route::delete('/admin/promo/{promo}', [PromoController::class, 'destroy'])->name('admin.promo.destroy');

// ─── Reviews (ADMIN) ─────────────────────────────────────────
Route::get('/admin/reviews', [ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
Route::patch('/admin/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
Route::patch('/admin/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('admin.reviews.reject');
Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');


// ─── API ───────────────────────────────────────────
// ─── Booking API (untuk kalender) ────────────────────────────
Route::get('/api/unavailable-dates', [BookingController::class, 'unavailableDates'])->name('booking.unavailable');
Route::post('/api/apply-promo', [BookingController::class, 'applyPromo'])->name('booking.applyPromo');

use App\Http\Controllers\PaymentController;

Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/return', [PaymentController::class, 'returnPage'])->name('payment.return');






Route::get('/api/test-promo', function() {
    $promo = \App\Models\Promo::first();
    return response()->json([
        'table_exists' => true,
        'promo_count'  => \App\Models\Promo::count(),
        'sample'       => $promo,
    ]);
});