<?php

namespace App\Http\Controllers;

use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\Promo;
use App\Models\VillaPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // ── FR-01: Tampil form booking ────────────────────────────────
    public function form()
    {
        $bookedDates = $this->getBookedDates();
        $pricePerNight = $this->findPriceForDate(today()->toDateString());
        $priceConfigMissing = $pricePerNight === null;

        return view('booking.form', compact('bookedDates', 'pricePerNight', 'priceConfigMissing'));
    }

    // ── FR-02: Simpan form booking ke session ────────────────────
    public function storeForm(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:10',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string|max:5',
        ]);

        if (! $this->isDateAvailable($request->check_in, $request->check_out)) {
            return back()->withErrors(['check_in' => 'Tanggal yang dipilih sudah tidak tersedia.'])->withInput();
        }

        try {
            $pricePerNight = $this->getPriceForDate($request->check_in);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['check_in' => $e->getMessage()])->withInput();
        }

        $nights = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));
        $subtotal = $pricePerNight * $nights;
        $discount = 0;
        $promoCode = null;

        if ($request->filled('promo_code')) {
            $checkInDate = Carbon::parse($request->check_in)->toDateString();
            $promo = Promo::where('code', strtoupper($request->promo_code))
                ->where('is_active', true)
                ->whereDate('valid_from', '<=', $checkInDate)
                ->whereDate('valid_until', '>=', $checkInDate)
                ->first();

            if ($promo) {
                $discount = $subtotal * ($promo->discount_percent / 100);
                $promoCode = $promo->code;
            }
        }

        $session = array_merge($request->all(), [
            'price_per_night' => $pricePerNight,
            'nights' => $nights,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_price' => $subtotal - $discount,
            'promo_code' => $promoCode,
        ]);

        // Bersihkan session dari booking sebelumnya agar bisa membuat booking baru
        session()->forget(['booking_code', 'payment_order_id']);

        session(['booking' => $session]);

        return redirect()->route('booking.confirmation');
    }

    // ── FR-03: Halaman konfirmasi ─────────────────────────────────
    public function confirmation()
    {
        $booking = session('booking');
        if (! $booking) {
            return redirect()->route('booking.form');
        }

        // Kalau sudah ada booking_code di session, berarti sudah dikonfirmasi
        // redirect ke invoice supaya tidak bisa konfirmasi ulang
        if (session('booking_code')) {
            return redirect()->route('booking.invoice');
        }

        return view('booking.confirmation', compact('booking'));
    }

    // ── FR-04: Simpan booking ke database ────────────────────────
    public function storeConfirmation(Request $request)
    {
        $data = session('booking');
        if (! $data) {
            return redirect()->route('booking.form');
        }

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'guests' => $data['guests'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'country' => $data['country'] ?? 'ID',
            'promo_code' => $data['promo_code'] ?? null,
            'price_per_night' => $data['price_per_night'],
            'discount_amount' => $data['discount_amount'],
            'total_price' => $data['total_price'],
            'status' => 'PENDING',
            'expires_at' => now()->addHour(),
        ]);

        session(['booking_code' => $booking->booking_code]);
        session()->forget('booking'); // hapus data form supaya tidak bisa konfirmasi ulang

        return redirect()->route('booking.invoice');
    }

    // ── FR-05: Invoice ────────────────────────────────────────────
    public function invoice()
    {
        // Guard: harus punya booking_code di session
        $code = session('booking_code');
        if (! $code) {
            return redirect()->route('booking.form');
        }

        $booking = Booking::where('booking_code', $code)->first();
        if (! $booking) {
            return redirect()->route('booking.form');
        }

        // Jika booking sudah terbayar, arahkan langsung ke halaman status (Done)
        if ($booking->status === 'CONFIRMED') {
            return redirect()->route('booking.status', ['code' => $booking->booking_code]);
        }

        return view('booking.invoice', compact('booking'));
    }

    // ── Invoice PDF ───────────────────────────────────────────────
    public function invoicePdf(Request $request)
    {
        $code = $request->query('code') ?? session('booking_code');
        if (! $code) {
            return redirect()->route('booking.form');
        }

        $booking = Booking::where('booking_code', strtoupper($code))->first();
        if (! $booking) {
            return redirect()->route('booking.form');
        }

        return view('booking.invoice-pdf', compact('booking'));
    }

    // ── FR-06: Pending page ───────────────────────────────────────
    public function pending(Request $request)
    {
        $code = $request->query('code') ?? session('booking_code');
        if (! $code) {
            return redirect()->route('booking.form');
        }

        $booking = Booking::where('booking_code', strtoupper($code))->first();
        if (! $booking) {
            return redirect()->route('booking.form');
        }

        // Auto-cancel kalau sudah expired
        if ($booking->status === 'PENDING' && $booking->isExpired()) {
            $booking->update(['status' => 'CANCELLED']);
        }

        $expiresAt = Carbon::parse($booking->expires_at);
        $now = now();

        if ($expiresAt <= $now) {
            $timeRemaining = '00:00:00';
        } else {
            $diff = $expiresAt->diff($now);
            $timeRemaining = sprintf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
        }

        return view('booking.pending-clean', compact('booking', 'timeRemaining'));
    }

    // ── FR-08: Status page ────────────────────────────────────────
    public function status(Request $request)
    {
        $booking = null;
        if ($request->filled('code')) {
            $booking = Booking::where('booking_code', strtoupper($request->code))->first();

            if ($booking && $booking->status === 'PENDING' && $booking->isExpired()) {
                $booking->update(['status' => 'CANCELLED']);
            }

            if ($booking && $booking->status === 'CONFIRMED') {
                return view('booking.done-clean', compact('booking'));
            }
        }

        return view('booking.status', compact('booking'));
    }

    // ── Apply Promo ───────────────────────────────────────────────
    public function applyPromo(Request $request)
    {
        $code = strtoupper(trim($request->promo_code ?? ''));
        $checkIn = Carbon::parse($request->check_in ?? now())->toDateString();

        if (! $code) {
            return response()->json(['valid' => false, 'message' => 'Please enter a promo code.']);
        }

        $promo = Promo::where('code', $code)
            ->where('is_active', true)
            ->whereDate('valid_from', '<=', $checkIn)
            ->whereDate('valid_until', '>=', $checkIn)
            ->first();

        if ($promo) {
            return response()->json([
                'valid' => true,
                'discount_percent' => (float) $promo->discount_percent,
                'message' => "Promo applied! {$promo->discount_percent}% off.",
            ]);
        }

        return response()->json(['valid' => false, 'message' => 'Invalid promo code.']);
    }

    // ── ADMIN: Manual Booking ─────────────────────────────────────
    public function storeManualBooking(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guest_name' => 'required|string|max:200',
            'phone' => 'required|string|max:20',
            'guests' => 'required|integer|min:1|max:10',
            'email' => 'required|email',
            'notes' => 'nullable|string|max:255',
        ]);

        if (! $this->isDateAvailable($request->check_in, $request->check_out)) {
            return back()->withErrors(['check_in' => 'Tanggal yang dipilih sudah tidak tersedia.'])->withInput();
        }

        $nameParts = preg_split('/\s+/', trim($request->guest_name), 2);
        $firstName = $nameParts[0] ?? 'Guest';
        $lastName = $nameParts[1] ?? '';

        Booking::create([
            'booking_code' => Booking::generateCode(),
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => 'ID',
            'promo_code' => null,
            'price_per_night' => 0,
            'discount_amount' => 0,
            'total_price' => 0,
            'status' => 'CONFIRMED',
            'is_manual' => true,
            'expires_at' => null,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.booking_list')->with('success', 'Manual booking berhasil disimpan.');
    }

    // ── API: Unavailable Dates ────────────────────────────────────
    public function unavailableDates()
    {
        return response()->json($this->getBookedDates());
    }

    // ── API: Calendar Data (Admin) ────────────────────────────────
    public function getCalendarData(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $bookings = Booking::whereIn('status', ['PENDING', 'CONFIRMED'])
            ->where('check_out', '>', $startOfMonth)
            ->where('check_in', '<', $endOfMonth->copy()->addDay())
            ->get(['check_in', 'check_out', 'status']);

        $bookedDates = [];
        foreach ($bookings as $b) {
            $current = Carbon::parse($b->check_in)->copy();
            $end = Carbon::parse($b->check_out);
            while ($current->lte($end)) {
                $bookedDates[$current->toDateString()] = [
                    'status' => $b->status,
                    'type' => 'booking',
                ];
                $current->addDay();
            }
        }

        $blockedDates = $this->getBlockedDateDetails($startOfMonth->toDateString(), $endOfMonth->toDateString());

        return response()->json([
            'booked' => $bookedDates,
            'blocked' => $blockedDates,
        ]);
    }

    // ── ADMIN: Update Booking ─────────────────────────────────────
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED',
        ]);

        $conflicting = Booking::whereIn('status', ['PENDING', 'CONFIRMED'])
            ->where('id', '!=', $id)
            ->where('check_in', '<', $validated['check_out'])
            ->where('check_out', '>', $validated['check_in'])
            ->exists();

        if ($conflicting || $this->hasBlockedDateConflict($validated['check_in'], $validated['check_out'])) {
            return response()->json(['message' => 'Tanggal sudah dipesan oleh booking lain.'], 422);
        }

        $nights = Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out']));
        $subtotal = $validated['price_per_night'] * $nights;
        $totalPrice = $subtotal - $validated['discount_amount'];

        $booking->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests' => $validated['guests'],
            'price_per_night' => $validated['price_per_night'],
            'discount_amount' => $validated['discount_amount'],
            'total_price' => $totalPrice,
            'status' => $validated['status'],
        ]);

        return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking]);
    }

    // ── ADMIN: Delete Booking ─────────────────────────────────────
    public function destroyBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.booking_list')->with('success', 'Booking deleted successfully');
    }

    // ─────────────────────────────────────────────────────────────
    // HELPER METHODS
    // ─────────────────────────────────────────────────────────────

    private function getBookedDates(): array
    {
        $bookings = Booking::where(function ($q) {
            $q->where('status', 'CONFIRMED')
                ->orWhere(function ($q2) {
                    $q2->where('status', 'PENDING')
                        ->where('expires_at', '>', now());
                });
        })
            ->where('check_out', '>=', today())
            ->get(['check_in', 'check_out', 'status']);

        $dates = [];
        foreach ($bookings as $b) {
            $current = Carbon::parse($b->check_in)->copy();
            $end = Carbon::parse($b->check_out);
            while ($current->lte($end)) {
                $dates[$current->toDateString()] = $b->status;
                $current->addDay();
            }
        }

        foreach ($this->getBlockedDateDetails(today()->toDateString()) as $date => $detail) {
            $dates[$date] = strtoupper($detail['type']);
        }

        return $dates;
    }

    private function isDateAvailable(string $checkIn, string $checkOut): bool
    {
        $hasBookingConflict = Booking::where(function ($q) {
            $q->where('status', 'CONFIRMED')
                ->orWhere(function ($q2) {
                    $q2->where('status', 'PENDING')
                        ->where('expires_at', '>', now());
                });
        })
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->exists();

        return ! $hasBookingConflict && ! $this->hasBlockedDateConflict($checkIn, $checkOut);
    }

    private function getPriceForDate(string $date): float
    {
        $price = $this->findPriceForDate($date);

        if ($price === null) {
            throw new \RuntimeException('The active villa price has not been configured for the selected date. Please contact the admin.');
        }

        return $price;
    }

    private function findPriceForDate(string $date): ?float
    {
        $price = VillaPrice::where('is_active', true)
            ->where('valid_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', $date);
            })
            ->orderByDesc('valid_from')
            ->first();

        return $price ? (float) $price->price_per_night : null;
    }

    private function hasBlockedDateConflict(string $checkIn, string $checkOut): bool
    {
        return BlockedDate::where('blocked_date', '<', $checkOut)
            ->where(function ($q) use ($checkIn) {
                $q->whereNull('end_date')
                    ->where('blocked_date', '>=', $checkIn)
                    ->orWhere(function ($q2) use ($checkIn) {
                        $q2->whereNotNull('end_date')
                            ->where('end_date', '>=', $checkIn);
                    });
            })
            ->exists();
    }

    private function getBlockedDateDetails(?string $from = null, ?string $to = null): array
    {
        $query = BlockedDate::query();

        if ($from) {
            $query->where(function ($q) use ($from) {
                $q->whereNull('end_date')
                    ->where('blocked_date', '>=', $from)
                    ->orWhere(function ($q2) use ($from) {
                        $q2->whereNotNull('end_date')
                            ->where('end_date', '>=', $from);
                    });
            });
        }

        if ($to) {
            $query->where('blocked_date', '<=', $to);
        }

        $dates = [];
        foreach ($query->get(['blocked_date', 'end_date', 'reason', 'type']) as $blocked) {
            $current = Carbon::parse($blocked->blocked_date);
            $end = $blocked->end_date ? Carbon::parse($blocked->end_date) : $current->copy();

            while ($current->lte($end)) {
                $dates[$current->toDateString()] = [
                    'status' => strtoupper($blocked->type),
                    'type' => $blocked->type,
                    'reason' => $blocked->reason,
                ];
                $current->addDay();
            }
        }

        return $dates;
    }
}
