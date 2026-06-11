<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BlockedDateController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VillaPriceController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\GuestReview;
use App\Models\Promo;
use App\Models\VillaPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', function () {
    $testimonials = GuestReview::where('status', 'approved')
        ->latest()
        ->get();

    return view('index', compact('testimonials'));
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
    Route::get('/pending', [BookingController::class, 'pending'])->name('pending');
    Route::get('/status', [BookingController::class, 'status'])->name('status');
});

// ─── Admin Authentication ────────────────────────────────────
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

// ─── Admin Routes (Protected) ────────────────────────────────
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::post('/notifications/read-all', function () {
        session(['admin_confirmed_notifications_read_at' => now()]);
        return redirect()->back();
    })->name('notifications.read_all');

    Route::get('/dashboard', function () {
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'PENDING')->count();
        $confirmedBookings = Booking::where('status', 'CONFIRMED')->count();
        $cancelledBookings = Booking::where('status', 'CANCELLED')->count();
        $revenue = Booking::where('status', 'CONFIRMED')->sum('total_price');
        $basePrice = VillaPrice::where('label', 'Base Price')
            ->whereNull('valid_until')
            ->latest()
            ->first();
        $todayDate = today()->toDateString();
        $todayPrice = VillaPrice::where('is_active', true)
            ->where(fn ($query) => $query
                ->where('label', '!=', 'Base Price')
                ->orWhereNull('label'))
            ->whereDate('valid_from', '<=', $todayDate)
            ->where(fn ($query) => $query
                ->whereNull('valid_until')
                ->orWhereDate('valid_until', '>=', $todayDate))
            ->orderByDesc('valid_from')
            ->latest()
            ->first() ?? $basePrice;
        $priceView = request()->query('price_view') === 'base' ? 'base' : 'today';
        $displayPrice = $priceView === 'base' ? $basePrice : $todayPrice;
        $recentBookings = Booking::orderByDesc('created_at')
            ->paginate(6, ['*'], 'transactions_page')
            ->withQueryString();

        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'revenue',
            'basePrice',
            'todayPrice',
            'priceView',
            'displayPrice',
            'recentBookings'
        ));
    })->name('dashboard');

    Route::get('/edit-profile', function () {
        $admin = Admin::find(session('admin_id'));
        return view('admin.edit_profile', compact('admin'));
    })->name('edit_profile');

    Route::post('/edit-profile', function (Request $request) {
        $admin = Admin::findOrFail(session('admin_id'));
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);
        $admin->name  = $validated['name'];
        $admin->email = $validated['email'];
        if (!empty($validated['password'])) {
            $admin->password = $validated['password'];
        }
        $admin->save();
        session([
            'admin_name'  => $admin->name,
            'admin_email' => $admin->email,
        ]);
        return redirect()->route('admin.dashboard')->with('profile_updated', true);
    })->name('edit_profile.submit');

    Route::get('/manual-booking', function () {
        return view('admin.manual_booking');
    })->name('manual_booking');

    Route::post('/manual-booking', [BookingController::class, 'storeManualBooking'])->name('manual_booking.submit');

    Route::get('/booking-list', function (Request $request) {
        $q      = $request->query('q');
        $status = $request->query('status');
        $query  = Booking::query();
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('booking_code', 'like', "%{$q}%");
            });
        }
        if ($status) {
            $map    = ['pending' => 'PENDING', 'confirmed' => 'CONFIRMED', 'declined' => 'CANCELLED'];
            $wanted = $map[strtolower($status)] ?? null;
            if ($wanted) {
                $query->where('status', $wanted);
            }
        }
        $bookings = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('admin.booking_list', compact('bookings'));
    })->name('booking_list');

    Route::put('/booking-list/{id}', [BookingController::class, 'updateBooking'])->name('booking.update');
    Route::delete('/booking-list/{id}', [BookingController::class, 'destroyBooking'])->name('booking.destroy');

    Route::get('/availability-calendar', function () {
        return view('admin.calendar');
    })->name('calendar');

    Route::get('/blocked-dates', [BlockedDateController::class, 'index'])->name('blocked_dates.index');
    Route::post('/blocked-dates', [BlockedDateController::class, 'store'])->name('blocked_dates.store');
    Route::put('/blocked-dates/{blockedDate}', [BlockedDateController::class, 'update'])->name('blocked_dates.update');
    Route::delete('/blocked-dates/{blockedDate}', [BlockedDateController::class, 'destroy'])->name('blocked_dates.destroy');

    Route::get('/villa-settings', function (Request $request) {
        $basePrice = VillaPrice::where('label', 'Base Price')
            ->whereNull('valid_until')
            ->latest()
            ->first();
        $todayDate = today()->toDateString();
        $todayPrice = VillaPrice::where('is_active', true)
            ->where(fn ($query) => $query
                ->where('label', '!=', 'Base Price')
                ->orWhereNull('label'))
            ->whereDate('valid_from', '<=', $todayDate)
            ->where(fn ($query) => $query
                ->whereNull('valid_until')
                ->orWhereDate('valid_until', '>=', $todayDate))
            ->orderByDesc('valid_from')
            ->latest()
            ->first() ?? $basePrice;
        $priceView     = $request->query('price_view') === 'base' ? 'base' : 'today';
        $displayPrice  = $priceView === 'base' ? $basePrice : $todayPrice;
        $seasonalPrices = VillaPrice::orderByDesc('valid_from')
            ->where(fn ($query) => $query
                ->where('label', '!=', 'Base Price')
                ->orWhereNull('label'))
            ->orderByDesc('created_at')
            ->get();
        $promos = Promo::orderByDesc('created_at')->get();
        return view('admin.villa_settings', compact('basePrice', 'todayPrice', 'priceView', 'displayPrice', 'seasonalPrices', 'promos'));
    })->name('villa_settings');

    Route::post('/villa-settings/base-price', function (Request $request) {
        $request->validate(['base_price' => 'required|numeric|min:0']);
        VillaPrice::updateOrCreate(
            ['label' => 'Base Price', 'valid_until' => null],
            ['price_per_night' => $request->base_price, 'label' => 'Base Price', 'valid_from' => now()->toDateString(), 'valid_until' => null, 'is_active' => true]
        );
        return redirect()->route('admin.villa_settings')->with('success', 'Base price updated successfully!');
    })->name('villa_settings.base_price');

    Route::post('/villa-settings', function (Request $request) {
        $request->validate([
            'promo_name'       => 'required|string|max:100',
            'promo_code'       => 'required|string|max:50',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'valid_from'       => 'required|date',
            'valid_until'      => 'required|date|after_or_equal:valid_from',
            'promo_status'     => 'required|in:active,inactive',
        ]);
        Promo::updateOrCreate(
            ['code' => strtoupper($request->promo_code)],
            ['name' => $request->promo_name, 'discount_percent' => $request->discount_percent, 'valid_from' => $request->valid_from, 'valid_until' => $request->valid_until, 'is_active' => $request->promo_status === 'active']
        );
        return redirect()->back()->with('success', "Promo '{$request->promo_name}' successfully saved!");
    })->name('villa_settings.save');

    Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
    Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
    Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');

    Route::post('/villa-prices', [VillaPriceController::class, 'store'])->name('villa_prices.store');
    Route::get('/villa-prices/{villaPrice}/edit', [VillaPriceController::class, 'edit'])->name('villa_prices.edit');
    Route::put('/villa-prices/{villaPrice}', [VillaPriceController::class, 'update'])->name('villa_prices.update');
    Route::delete('/villa-prices/{villaPrice}', [VillaPriceController::class, 'destroy'])->name('villa_prices.destroy');

    // ─── Reviews (ADMIN) ─────────────────────────────────────────
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ─── CMS: Home Content ────────────────────────────────────────
    Route::get('/home-content', [HomeContentController::class, 'index'])->name('home_content.index');
    Route::put('/home-content', [HomeContentController::class, 'update'])->name('home_content.update');

    // ─── CMS: Gallery ─────────────────────────────────────────────
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery/upload', [GalleryController::class, 'upload'])->name('gallery.upload');
    Route::get('/gallery/{gallery}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::patch('/gallery/{gallery}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::post('/gallery/reorder', [GalleryController::class, 'reorder'])->name('gallery.reorder');

    // ─── CMS: Media Library ───────────────────────────────────────
    Route::get('/media-library', [MediaLibraryController::class, 'index'])->name('media_library');
});

// ─── API ─────────────────────────────────────────────────────
Route::get('/api/unavailable-dates', [BookingController::class, 'unavailableDates'])->name('booking.unavailable');
Route::get('/api/calendar-data', [BookingController::class, 'getCalendarData'])->name('booking.calendarData');
Route::post('/api/apply-promo', [BookingController::class, 'applyPromo'])->name('booking.applyPromo');
Route::post('/api/booking-price', [BookingController::class, 'pricePreview'])->name('booking.pricePreview');

Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/return', [PaymentController::class, 'returnPage'])->name('payment.return');

if (config('app.debug')) {
    Route::get('/debug/clear-booking', function () {
        session()->forget(['booking', 'booking_code', 'payment_order_id']);
        return response('Booking session cleared', 200);
    });
}