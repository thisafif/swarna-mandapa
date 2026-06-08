@extends('layouts.app')
@section('title', 'Payment Pending — Swarna Mandapa')

@push('styles')
<style>
    body { background: #FDFBF7; }
    
    .pending-container {
        max-width: 700px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .pending-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .pending-status-badge {
        display: inline-block;
        background: #FEF3C7;
        color: #92400e;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 1rem;
        letter-spacing: 0.05em;
    }

    .pending-title {
        font-size: 32px;
        font-weight: 600;
        color: #333;
        margin: 0 0 0.5rem;
        font-family: 'Georgia', serif;
    }

    .pending-subtitle {
        font-size: 14px;
        color: #999;
        margin: 0;
    }

    .timer-section {
        background: linear-gradient(135deg, #FFFBF0 0%, #FFF8EB 100%);
        border: 2px solid #F0E5D3;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        margin: 2rem 0;
    }

    .timer-label {
        font-size: 12px;
        color: #8B6914;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .timer-display {
        font-size: 56px;
        font-weight: bold;
        color: #C9A96E;
        font-family: 'Courier New', monospace;
        margin: 1rem 0;
        line-height: 1.2;
    }

    .timer-message {
        font-size: 12px;
        color: #8B6914;
    }

    .card {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 1.5rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .detail-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:nth-child(odd):nth-last-child(-n+2),
    .detail-item:nth-child(even):nth-last-child(-n+2) {
        border-bottom: none;
    }

    .detail-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    .pricing-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .pricing-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .pricing-table td {
        padding: 0.75rem 0;
        font-size: 13px;
    }

    .pricing-table .label {
        color: #666;
    }

    .pricing-table .amount {
        text-align: right;
        font-weight: 500;
        color: #333;
    }

    .pricing-table tr.total {
        border-top: 2px solid #C9A96E;
        border-bottom: 2px solid #C9A96E;
    }

    .pricing-table tr.total td {
        padding: 1rem 0;
        font-weight: 600;
        font-size: 14px;
    }

    .pricing-table .total .amount {
        color: #C9A96E;
        font-size: 16px;
        font-family: 'Georgia', serif;
    }

    .pricing-table .discount {
        color: #22c55e !important;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #C9A96E 0%, #8B6914 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(201, 169, 110, 0.3);
    }

    .contact-info {
        background: #FFFBF0;
        border-left: 4px solid #C9A96E;
        border-radius: 6px;
        padding: 1.5rem;
        margin-top: 2rem;
        font-size: 13px;
    }

    .contact-info-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }

    .contact-item {
        margin-bottom: 0.75rem;
        color: #666;
        line-height: 1.5;
    }

    .contact-item a {
        color: #C9A96E;
        text-decoration: none;
    }

    .contact-item a:hover {
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .pending-title {
            font-size: 24px;
        }

        .timer-display {
            font-size: 42px;
        }
    }
</style>
@endpush

@section('content')

@php
    $bookingCode   = $booking->booking_code ?? 'SWM-XXXXXX';
    $guestName     = isset($booking->first_name)
                      ? trim($booking->first_name . ' ' . $booking->last_name)
                      : 'Guest';
    $email         = $booking->email ?? '—';
    $phone         = $booking->phone ?? '—';
    $guests        = $booking->guests ?? '—';

    $total         = isset($booking->total_price) ? (int) $booking->total_price : 0;
    $pricePerNight = isset($booking->price_per_night) ? (int) $booking->price_per_night : 0;
    $discount      = isset($booking->discount_amount) ? (int) $booking->discount_amount : 0;
    $promoCode     = $booking->promo_code ?? null;

    $ci     = $booking->check_in  ?? null;
    $co     = $booking->check_out ?? null;
    $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;
    $nightlyBreakdown = $booking->nightly_price_breakdown ?? [];
    $base = $nightlyBreakdown
        ? collect($nightlyBreakdown)->sum(fn ($night) => (int) ($night['price'] ?? 0))
        : $pricePerNight * $nights;
    if ($total > 0 && $base === 0) {
        $base = $total + $discount;
    }
@endphp

<div class="pending-container">
    <!-- Header -->
    <div class="pending-header">
        <div class="pending-status-badge">Awaiting Payment</div>
        <h1 class="pending-title">Payment Pending</h1>
        <p class="pending-subtitle">Please complete your payment to confirm this booking</p>
    </div>

    <!-- Timer -->
    <div class="timer-section">
        <div class="timer-label">Complete Payment Within</div>
        <div class="timer-display" id="countdown">{{ $timeRemaining }}</div>
        <div class="timer-message">Your booking will be cancelled if time expires</div>
    </div>

    <!-- Booking Reference -->
    <div class="card">
        <div class="card-title">Booking Reference</div>
        <div class="detail-grid" style="grid-template-columns: 1fr;">
            <div class="detail-item" style="border-bottom: none;">
                <div class="detail-label">Booking Code</div>
                <div class="detail-value" style="font-family: monospace; font-size: 16px;">{{ $bookingCode }}</div>
            </div>
        </div>
    </div>

    <!-- Guest Details -->
    <div class="card">
        <div class="card-title">Guest Information</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Guest Name</div>
                <div class="detail-value">{{ $guestName }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $email }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Phone</div>
                <div class="detail-value">{{ $phone }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Guests</div>
                <div class="detail-value">{{ $guests }} person</div>
            </div>
        </div>
    </div>

    <!-- Stay Details -->
    <div class="card">
        <div class="card-title">Stay Details</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Check-in</div>
                <div class="detail-value">{{ $ci ? date('d M Y', strtotime($ci)) : '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Check-out</div>
                <div class="detail-value">{{ $co ? date('d M Y', strtotime($co)) : '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration</div>
                <div class="detail-value">{{ $nights }} {{ $nights === 1 ? 'Night' : 'Nights' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Rate per Night</div>
                <div class="detail-value">Rp {{ number_format($pricePerNight, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Pricing Summary -->
    <div class="card">
        <div class="card-title">Amount Due</div>
        <table class="pricing-table">
            <tr>
                <td class="label">Room Charges</td>
                <td class="amount">Rp {{ number_format($base, 0, ',', '.') }}</td>
            </tr>
            @if($discount > 0)
            <tr>
                <td class="label discount">Discount {{ $promoCode ? '(' . $promoCode . ')' : '' }}</td>
                <td class="amount discount">-Rp {{ number_format($discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total">
                <td class="label">Total</td>
                <td class="amount total">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="action-buttons">
            <a href="{{ route('booking.invoice') }}" class="btn btn-primary">
                Complete Payment
            </a>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="contact-info">
        <div class="contact-info-title">Need Help?</div>
        <div class="contact-item">
            Phone: <a href="tel:+6427297357">+64 27 297 3575</a>
        </div>
        <div class="contact-item">
            Email: <a href="mailto:reservations@swarnamandapa.com">reservations@swarnamandapa.com</a>
        </div>
        <div class="contact-item">
            Address: Jl. Nuansa Angkasa III No.7 & 9, Ungasan, Kec. Kuta Sel., Kabupaten Badung, Bali 80361
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('countdown');
    if (!timerElement) return;

    // Pakai expires_at dari database sebagai acuan — tidak reset saat refresh
    const expiresAt = {{ \Carbon\Carbon::parse($booking->expires_at)->timestamp }} * 1000;

    const updateTimer = () => {
        const diff = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
        const h = String(Math.floor(diff / 3600)).padStart(2, '0');
        const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
        const s = String(diff % 60).padStart(2, '0');

        timerElement.textContent = `${h}:${m}:${s}`;

        if (diff === 0) {
            timerElement.style.color = '#ef4444';
            timerElement.textContent = '00:00:00';
            // Auto redirect ke status page kalau expired
            setTimeout(() => {
                window.location.href = '/booking/status?code={{ $booking->booking_code }}';
            }, 2000);
        } else {
            setTimeout(updateTimer, 1000);
        }
    };

    updateTimer();
});
</script>
@endpush

@endsection
