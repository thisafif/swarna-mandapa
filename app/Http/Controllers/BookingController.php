<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Promo;
use App\Models\VillaPrice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ── FR-01: Tampil form booking ────────────────────────────────
    public function form()
    {
        // Kirim tanggal-tanggal yang tidak tersedia ke kalender
        $bookedDates = $this->getBookedDates();
        return view('booking.form', compact('bookedDates'));
    }

    // ── FR-02: Simpan form booking ke session ────────────────────
    public function storeForm(Request $request)
{
    $request->validate([
        'check_in'      => 'required|date|after_or_equal:today',
        'check_out'     => 'required|date|after:check_in',
        'guests'        => 'required|integer|min:1|max:10',
        'first_name'    => 'required|string|max:100',
        'last_name'     => 'required|string|max:100',
        'email'         => 'required|email:rfc,dns',
        'phone'         => 'required|string|regex:/^\+\d{1,3}\d{6,}$/',
        'arrival_time'  => 'required|string',
        'payment_method' => 'required|in:card,va,ewallet',
    ]);

    // #9 — Minimal 2 malam
    $nights = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));
    if ($nights < 2) {
        return back()->withErrors(['check_out' => 'Minimum stay adalah 2 malam.'])->withInput();
    }

    // Auto-cancel booking PENDING yang sudah expired
    Booking::where('status', 'PENDING')
    ->where('expires_at', '<', now())
    ->update(['status' => 'CANCELLED']);

    // Cek ketersediaan tanggal
    if (!$this->isDateAvailable($request->check_in, $request->check_out)) {
        return back()->withErrors(['check_in' => 'Tanggal yang dipilih sudah tidak tersedia.'])->withInput();
    }

    // #10 — Harga tanpa tax & service fee
    $pricePerNight = $this->getPriceForDate($request->check_in);
    $subtotal      = $pricePerNight * $nights;  // total = harga villa saja
    $discount      = 0;
    $promoCode     = null;

    // Terapkan promo jika ada
$discount  = 0;
$promoCode = null;

if ($request->filled('promo_code')) {
    $promoCode = strtoupper($request->promo_code);

    // Cek di database
    $promo = Promo::where('code', $promoCode)
        ->where('is_active', true)
        ->where('valid_from', '<=', $request->check_in)
        ->where('valid_until', '>=', $request->check_in)
        ->first();

    if ($promo) {
        $discount = $subtotal * ($promo->discount_percent / 100);
    } else {
        $promoCode = null; // kode tidak valid
    }
}
    $totalPrice = $subtotal - $discount;

    session(['booking' => array_merge($request->all(), [
        'price_per_night' => $pricePerNight,
        'nights'          => $nights,
        'subtotal'        => $subtotal,
        'discount_amount' => $discount,
        'total_price'     => $totalPrice,
        'promo_code'      => $promoCode,
    ])]);

    return redirect()->route('booking.confirmation');
}
    // ── FR-03: Halaman konfirmasi ─────────────────────────────────
    public function confirmation()
    {
        $booking = session('booking');
        if (!$booking) return redirect()->route('booking.form');
        return view('booking.confirmation', compact('booking'));
    }

    // ── FR-04: Simpan booking ke database ────────────────────────
    public function storeConfirmation(Request $request)
{
    $data = session('booking');
    if (!$data) return redirect()->route('booking.form');

    $booking = Booking::create([
        'booking_code'    => Booking::generateCode(),
        'check_in'        => $data['check_in'],
        'check_out'       => $data['check_out'],
        'guests'          => $data['guests'],
        'first_name'      => $data['first_name'],
        'last_name'       => $data['last_name'],
        'email'           => $data['email'],
        'phone'           => $data['phone'],
        'promo_code'      => $data['promo_code'] ?? null,
        'price_per_night' => $data['price_per_night'],
        'discount_amount' => $data['discount_amount'],
        'total_price'     => $data['total_price'],  // sudah tanpa tax/fee
        'status'          => 'PENDING',
        'expires_at'      => now()->addHour(),
    ]);

    session(['booking_code' => $booking->booking_code]);
    return redirect()->route('booking.invoice');
}
    // ── FR-05: Invoice ────────────────────────────────────────────
    public function invoice()
    {
        $code    = session('booking_code');
        $booking = $code ? Booking::where('booking_code', $code)->first() : null;

        // Fallback ke session kalau booking belum tersimpan (dev mode)
        if (!$booking) {
            $data = session('booking', []);
            return view('booking.invoice', ['booking' => (object) $data, 'fromSession' => true]);
        }

        return view('booking.invoice', compact('booking'));
    }

    // ── PDF Invoice Download ────────────────────────────────────
    public function invoicePdf(Request $request)
    {
        $code    = $request->query('code') ?? session('booking_code');
        $booking = $code ? Booking::where('booking_code', strtoupper($code))->first() : null;

        if (!$booking) {
            return redirect()->route('booking.form');
        }

        return view('booking.invoice-pdf', compact('booking'));
    }

    // ── FR-06: Payment failed / pending page ─────────────────────
    public function pending(Request $request)
    {
        $code    = $request->query('code') ?? session('booking_code');
        $booking = $code ? Booking::where('booking_code', strtoupper($code))->first() : null;

        if (!$booking) {
            return redirect()->route('booking.form');
        }

        // Auto-cancel kalau sudah expired
        if ($booking->status === 'PENDING' && $booking->isExpired()) {
            $booking->update(['status' => 'CANCELLED']);
        }

        // Calculate time remaining
        $expiresAt = Carbon::parse($booking->expires_at);
        $now = now();
        
        if ($expiresAt <= $now) {
            $timeRemaining = '00:00:00';
        } else {
            $diff = $expiresAt->diff($now);
            $timeRemaining = sprintf(
                '%02d:%02d:%02d',
                $diff->h,
                $diff->i,
                $diff->s
            );
        }

        return view('booking.pending-clean', compact('booking', 'timeRemaining'));
    }

    // ── FR-08: Status page ────────────────────────────────────────
    public function status(Request $request)
    {
        $booking = null;
        if ($request->filled('code')) {
            $booking = Booking::where('booking_code', strtoupper($request->code))->first();

            // Auto-cancel kalau sudah expired
            if ($booking && $booking->status === 'PENDING' && $booking->isExpired()) {
                $booking->update(['status' => 'CANCELLED']);
            }
        }
        
        // Jika CONFIRMED, langsung tampilkan done page
        if ($booking && $booking->status === 'CONFIRMED') {
            return view('booking.done-clean', compact('booking'));
        }
        
        return view('booking.status', compact('booking'));
    }

    // ── API: Tanggal tidak tersedia (untuk kalender frontend) ─────
    public function unavailableDates()
    {
        return response()->json($this->getBookedDates());
    }

    // ── API: Check booking status (untuk payment detection) ────────
    public function bookingStatus($code)
    {
        $booking = Booking::where('booking_code', strtoupper($code))->first();
        
        if (!$booking) {
            return response()->json(['status' => 'NOT_FOUND'], 404);
        }

        return response()->json([
            'booking_code' => $booking->booking_code,
            'status'       => $booking->status,
            'paid_at'      => $booking->paid_at,
            'expires_at'   => $booking->expires_at,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // HELPER METHODS
    // ─────────────────────────────────────────────────────────────

    private function getBookedDates(): array
    {
        // Ambil booking aktif (PENDING atau CONFIRMED)
        $bookings = Booking::where(function ($q) {
        $q->where('status', 'CONFIRMED');
    })
    ->orWhere(function ($q) {
        $q->where('status', 'PENDING')
          ->where('expires_at', '>', now()); // hanya PENDING yang belum expired
    })
    ->where('check_out', '>=', today())
    ->get(['check_in', 'check_out', 'status', 'expires_at']);

        $dates = [];
        foreach ($bookings as $b) {
            $current = Carbon::parse($b->check_in)->copy();
            $end     = Carbon::parse($b->check_out);

            while ($current->lt($end)) {
                $dates[$current->toDateString()] = $b->status; // 'PENDING' atau 'CONFIRMED'
                $current->addDay();
            }
        }

        return $dates;
    }

   private function isDateAvailable(string $checkIn, string $checkOut): bool
{
    $conflict = Booking::where('check_in', '<', $checkOut)
        ->where('check_out', '>', $checkIn)
        ->where(function ($q) {
            $q->where('status', 'CONFIRMED')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'PENDING')
                     ->where('expires_at', '>', now());
              });
        })
        ->exists();

    return !$conflict;
}
    private function getPriceForDate(string $date): float
    {
        $price = VillaPrice::where('is_active', true)
            ->where('valid_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', $date);
            })
            ->orderByDesc('valid_from')
            ->first();

        return $price ? (float) $price->price_per_night : 5000000; // default 5jt
    }
}