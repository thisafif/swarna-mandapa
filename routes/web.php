<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
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

// ─── Admin Authentication ────────────────────────────────────
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

// ─── Admin Routes (Protected) ────────────────────────────────
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/edit-profile', function () {
        return view('admin.edit_profile');
    })->name('edit_profile');

    Route::post('/edit-profile', function (\Illuminate\Http\Request $request) {
        session([
            'admin_name'  => $request->input('name', 'EGA MUTIARA'),
            'admin_email' => $request->input('email', 'admin@gmail.com'),
        ]);

        return redirect()->route('admin.dashboard')->with('profile_updated', true);
    })->name('edit_profile.submit');

    Route::get('/manual-booking', function () {
        return view('admin.manual_booking');
    })->name('manual_booking');

    Route::get('/booking-list', function () {
        return view('admin.booking_list');
    })->name('booking_list');

    Route::get('/availability-calendar', function () {
        return view('admin.calendar');
    })->name('calendar');

    Route::get('/villa-settings', function () {
        return view('admin.villa_settings');
    })->name('villa_settings');

    // Base Price Update
    Route::post('/villa-settings/base-price', function (\Illuminate\Http\Request $request) {
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
    })->name('villa_settings.base_price');

    // Promo Update
    Route::post('/villa-settings', function (\Illuminate\Http\Request $request) {
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
    })->name('villa_settings.save');

    // ─── Promo Management ─────────────────────────────────────────
    Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
    Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
    Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');

    // ─── Reviews (ADMIN) ─────────────────────────────────────────
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// ─── API ───────────────────────────────────────────
// ─── Booking API (untuk kalender) ────────────────────────────
Route::get('/api/unavailable-dates', [BookingController::class, 'unavailableDates'])->name('booking.unavailable');
Route::post('/api/apply-promo', [BookingController::class, 'applyPromo'])->name('booking.applyPromo');

use App\Http\Controllers\PromoController;
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

Route::get('/api/test-promo', function() {
    $promo = \App\Models\Promo::first();
    return response()->json([
        'table_exists' => true,
        'promo_count'  => \App\Models\Promo::count(),
        'sample'       => $promo,
    ]);
});