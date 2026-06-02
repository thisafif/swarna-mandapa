@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg mb-4">
                <i class="fas fa-hourglass-end mr-2"></i>
                Pembayaran Tertunda
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Menunggu Pembayaran Anda</h1>
            <p class="text-gray-600">Pembayaran belum dikonfirmasi. Silakan selesaikan pembayaran untuk konfirmasi booking.</p>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <!-- Timer (Most Important) -->
            <div class="bg-red-50 border-2 border-red-300 rounded-lg p-6 mb-8 text-center">
                <p class="text-sm text-red-600 font-semibold mb-3">⏱️ Waktu Tersisa untuk Pembayaran</p>
                <p class="text-5xl font-bold text-red-600 font-mono" id="countdown-timer">{{ $timeRemaining }}</p>
                <p class="text-xs text-red-500 mt-3">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Booking akan dibatalkan otomatis jika waktu habis
                </p>
            </div>

            <!-- Reference Info -->
            <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-sm text-gray-500 font-semibold">KODE BOOKING</p>
                    <p class="text-lg font-mono text-gray-800 mt-1">{{ $booking->booking_code }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded">
                    <p class="text-sm text-gray-500 font-semibold">STATUS</p>
                    <p class="text-lg font-semibold text-yellow-600 mt-1">
                        <i class="fas fa-clock mr-2"></i>MENUNGGU PEMBAYARAN
                    </p>
                </div>
            </div>

            <!-- Guest Details -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-800 mb-4 text-lg">🏨 Detail Reservasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">NAMA TAMU</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ $booking->first_name }} {{ $booking->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">EMAIL</p>
                        <p class="text-gray-800 mt-1 break-all">{{ $booking->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">TELEPON</p>
                        <p class="text-gray-800 mt-1">{{ $booking->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">JUMLAH TAMU</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ $booking->guests }} orang</p>
                    </div>
                </div>
            </div>

            <!-- Stay Details -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-800 mb-4 text-lg">📅 Tanggal Menginap</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded text-center">
                        <p class="text-xs text-gray-500 font-semibold">CHECK-IN</p>
                        <p class="text-lg font-semibold text-gray-800 mt-2">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') ?? \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-arrow-right text-gray-400 text-2xl"></i>
                    </div>
                    <div class="bg-blue-50 p-4 rounded text-center">
                        <p class="text-xs text-gray-500 font-semibold">CHECK-OUT</p>
                        <p class="text-lg font-semibold text-gray-800 mt-2">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') ?? \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="text-center mt-4 text-gray-700">
                    <p><strong>{{ $booking->number_of_nights ?? (\Carbon\Carbon::parse($booking->check_out)->diffInDays(\Carbon\Carbon::parse($booking->check_in))) }}</strong> malam</p>
                </div>
            </div>

            <!-- Pricing -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-800 mb-4 text-lg">💰 Ringkasan Harga</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga per malam</span>
                        <span class="font-semibold">Rp {{ number_format($booking->price_per_night, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah malam</span>
                        <span class="font-semibold">{{ $booking->number_of_nights ?? (\Carbon\Carbon::parse($booking->check_out)->diffInDays(\Carbon\Carbon::parse($booking->check_in))) }}</span>
                    </div>
                    @if($booking->discount_amount > 0)
                    <div class="flex justify-between text-green-600 border-t pt-2">
                        <span>Diskon ({{ $booking->promo_code }})</span>
                        <span class="font-semibold">-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold border-t pt-2 text-blue-600">
                        <span>TOTAL HARGA</span>
                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <a href="{{ route('booking.invoice') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg text-center transition">
                    <i class="fas fa-credit-card mr-2"></i>Lanjut Bayar
                </a>
                <a href="{{ route('booking.invoice') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-4 rounded-lg text-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Bayar
                </a>
            </div>

            <!-- Help Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="font-semibold text-blue-900 mb-4 text-lg">
                    <i class="fas fa-headset mr-2"></i>Butuh Bantuan?
                </h4>
                <div class="space-y-3 text-sm text-blue-800">
                    <div>
                        <p class="font-semibold">Hubungi Kami:</p>
                        <p class="flex items-center mt-1">
                            <i class="fas fa-phone mr-2 text-blue-600"></i>
                            <a href="tel:+6427297357" class="hover:underline">+64 27 297 3575</a>
                        </p>
                        <p class="flex items-center mt-1">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>
                            <a href="mailto:reservations@swarnamandapa.com" class="hover:underline">reservations@swarnamandapa.com</a>
                        </p>
                    </div>
                    <div class="text-xs pt-2">
                        <p class="font-semibold">Alamat:</p>
                        <p>Jl. Nuansa Angkasa III No.7 & 9, Ungasan, Kec. Kuta Sel., Kabupaten Badung, Bali 80361, Indonesia</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #countdown-timer {
        font-variant-numeric: tabular-nums;
    }
</style>

<script>
    // Update countdown timer setiap detik
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.getElementById('countdown-timer');
        if (!timerElement) return;

        // Parse waktu tersisa dari server (format: "HH:MM:SS")
        let [hours, minutes, seconds] = timerElement.textContent.trim().split(':').map(Number);
        let totalSeconds = hours * 3600 + minutes * 60 + seconds;

        if (isNaN(totalSeconds) || totalSeconds <= 0) {
            timerElement.innerHTML = '00:00:00';
            timerElement.closest('.bg-red-50').classList.add('bg-gray-100');
            timerElement.closest('.bg-red-50').classList.remove('bg-red-50');
            return;
        }

        setInterval(function() {
            totalSeconds--;

            if (totalSeconds <= 0) {
                timerElement.innerHTML = '00:00:00';
                timerElement.closest('.bg-red-50').classList.add('bg-gray-100');
                timerElement.closest('.bg-red-50').classList.remove('bg-red-50');
                return;
            }

            const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
            const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            const s = String(totalSeconds % 60).padStart(2, '0');

            timerElement.innerHTML = `${h}:${m}:${s}`;
        }, 1000);
    });
</script>
@endsection
