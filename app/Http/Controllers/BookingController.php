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
            'check_in'   => 'required|date|after_or_equal:today',
            'check_out'  => 'required|date|after:check_in',
            'guests'     => 'required|integer|min:1|max:10',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:20',
            'country'    => 'required|string|max:5',
        ]);

        // Cek ketersediaan tanggal
        if (!$this->isDateAvailable($request->check_in, $request->check_out)) {
            return back()->withErrors(['check_in' => 'Tanggal yang dipilih sudah tidak tersedia.'])->withInput();
        }

        // Hitung harga
        $pricePerNight = $this->getPriceForDate($request->check_in);
        $nights        = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));
        $subtotal      = $pricePerNight * $nights;
        $discount      = 0;
        $promoCode     = null;

        // Terapkan promo jika ada
        if ($request->filled('promo_code')) {
            $promo = Promo::where('code', strtoupper($request->promo_code))
                ->where('is_active', true)
                ->where('valid_from', '<=', $request->check_in)
                ->where('valid_until', '>=', $request->check_in)
                ->first();

            if ($promo) {
                $discount  = $subtotal * ($promo->discount_percent / 100);
                $promoCode = $promo->code;
            }
        }

        $session = array_merge($request->all(), [
            'price_per_night' => $pricePerNight,
            'nights'          => $nights,
            'subtotal'        => $subtotal,
            'discount_amount' => $discount,
            'total_price'     => $subtotal - $discount,
            'promo_code'      => $promoCode,
        ]);

        session(['booking' => $session]);
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
            'country'         => $data['country'] ?? 'ID',
            'promo_code'      => $data['promo_code'] ?? null,
            'price_per_night' => $data['price_per_night'],
            'discount_amount' => $data['discount_amount'],
            'total_price'     => $data['total_price'],
            'status'          => 'PENDING', // Menunggu pembayaran (1 jam)
            'expires_at'      => now()->addHour(), // 1 jam untuk bayar
        ]);

        // Simpan booking_code ke session untuk halaman berikutnya
        session(['booking_code' => $booking->booking_code]);

        // TODO FR-06: redirect ke Midtrans Snap (nanti pas payment gateway)
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
        return view('booking.status', compact('booking'));
    }

    // ── API: Tanggal tidak tersedia (untuk kalender frontend) ─────
    public function unavailableDates()
    {
        return response()->json($this->getBookedDates());
    }

    // ─────────────────────────────────────────────────────────────
    // HELPER METHODS
    // ─────────────────────────────────────────────────────────────

    private function getBookedDates(): array
    {
        // Ambil booking aktif (PENDING atau CONFIRMED)
        $bookings = Booking::whereIn('status', ['PENDING', 'CONFIRMED'])
            ->where('check_out', '>=', today())
            ->get(['check_in', 'check_out', 'status']);

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
        return !Booking::whereIn('status', ['PENDING', 'CONFIRMED'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->exists();
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