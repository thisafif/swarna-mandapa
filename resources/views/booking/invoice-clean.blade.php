@extends('layouts.app')
@section('title', 'Invoice & Payment — Swarna Mandapa')

@push('styles')
<style>
    /* Main Layout */
    body { background: #f8f6f1; }
    .invoice-container { max-width: 900px; margin: 0 auto; padding: 2rem 1rem; }
    
    /* Step Indicator */
    .steps { display: flex; justify-content: space-between; margin-bottom: 3rem; position: relative; }
    .steps::before { 
        content: ''; 
        position: absolute; 
        top: 15px; 
        left: 0; 
        right: 0; 
        height: 2px; 
        background: #e0e0e0; 
        z-index: 0;
    }
    .step { 
        flex: 1; 
        text-align: center; 
        position: relative; 
        z-index: 1;
    }
    .step-circle { 
        width: 40px; 
        height: 40px; 
        margin: 0 auto 0.5rem; 
        border-radius: 50%; 
        background: white; 
        border: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        color: #999;
    }
    .step.active .step-circle { border-color: #C9A96E; background: #C9A96E; color: white; }
    .step.done .step-circle { border-color: #22c55e; background: #22c55e; color: white; }
    .step-label { font-size: 12px; color: #999; text-transform: uppercase; }
    .step.active .step-label { color: #C9A96E; font-weight: bold; }
    .step.done .step-label { color: #22c55e; font-weight: bold; }

    /* Card Styling */
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: none; margin-bottom: 2rem; }
    .card-header { 
        background: linear-gradient(135deg, #C9A96E 0%, #8B6914 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-body { padding: 2rem; }
    .card-title { 
        font-size: 20px; 
        font-weight: 600; 
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .card-title i { font-size: 18px; }

    /* Badge */
    .badge-status { 
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
    }
    .badge-pending { background: #FEF3C7; color: #92400e; }
    .badge-paid { background: #D1FAE5; color: #065f46; }

    /* Grid */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
    .grid-item { }
    .grid-label { font-size: 12px; color: #666; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600; }
    .grid-value { font-size: 16px; color: #333; font-weight: 500; }

    /* Table */
    .invoice-table { width: 100%; border-collapse: collapse; font-size: 14px; margin: 1.5rem 0; }
    .invoice-table th { 
        border-bottom: 2px solid #e0e0e0;
        padding: 1rem 0;
        text-align: left;
        font-weight: 600;
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
    }
    .invoice-table td { padding: 1rem 0; border-bottom: 1px solid #f0f0f0; }
    .invoice-table tr:last-child td { border-bottom: 2px solid #e0e0e0; }
    .text-right { text-align: right; }
    .fw-bold { font-weight: 600; }
    .text-gold { color: #C9A96E; }

    /* Summary */
    .summary-row { display: flex; justify-content: space-between; padding: 0.75rem 0; font-size: 14px; }
    .summary-row.total { 
        font-size: 18px; 
        font-weight: 600; 
        color: #C9A96E;
        padding-top: 1rem;
        border-top: 2px solid #e0e0e0;
        margin-top: 1rem;
    }

    /* Timer */
    .timer-box { 
        background: #FEF3C7; 
        border: 2px solid #FCD34D;
        border-radius: 12px; 
        padding: 1.5rem; 
        text-align: center; 
        margin-bottom: 2rem;
    }
    .timer-label { font-size: 12px; color: #92400e; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem; }
    .timer-display { 
        font-size: 48px; 
        font-weight: bold; 
        color: #D97706;
        font-family: 'Courier New', monospace;
        margin: 0.5rem 0;
    }
    .timer-note { font-size: 12px; color: #92400e; }

    /* Buttons */
    .btn-group { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem; }
    .btn { 
        padding: 1rem; 
        border: none; 
        border-radius: 8px; 
        font-size: 14px; 
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .btn-primary { 
        background: linear-gradient(135deg, #C9A96E 0%, #8B6914 100%);
        color: white;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(201, 169, 110, 0.3); }
    .btn-secondary { 
        background: white;
        color: #333;
        border: 2px solid #e0e0e0;
    }
    .btn-secondary:hover { background: #f8f6f1; }

    /* Contact Info */
    .contact-box {
        background: #F0F9FF;
        border-left: 4px solid #3b82f6;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 2rem;
    }
    .contact-title { font-weight: 600; margin-bottom: 1rem; color: #1e40af; }
    .contact-item { 
        display: flex;
        gap: 0.75rem;
        font-size: 14px;
        color: #333;
        margin-bottom: 0.75rem;
    }
    .contact-item i { color: #3b82f6; min-width: 20px; }
    .contact-item a { color: #3b82f6; text-decoration: none; }
    .contact-item a:hover { text-decoration: underline; }

    /* Print */
    @media print {
        body { background: white; }
        .invoice-container { padding: 0; }
        .steps, .card-body button, .btn-group, .contact-box, .timer-box { display: none !important; }
        .card { box-shadow: none; margin-bottom: 0; }
        .card-header { background: #f5f5f5; color: #333; }
        .card-header .badge-status { display: none; }
    }

    @media (max-width: 768px) {
        .grid-2 { grid-template-columns: 1fr; gap: 1rem; }
        .btn-group { grid-template-columns: 1fr; }
        .card-body { padding: 1.5rem; }
    }
</style>
@endpush

@section('content')

@php
    $bookingCode   = $booking->booking_code ?? session('booking_code', 'SWM-' . date('Y') . '-XXXXXX');
    $guestName     = isset($booking->first_name)
                      ? trim($booking->first_name . ' ' . $booking->last_name)
                      : trim(session('booking.first_name','Guest').' '.session('booking.last_name',''));

    $total         = isset($booking->total_price) ? (int) $booking->total_price : 0;
    $pricePerNight = isset($booking->price_per_night) ? (int) $booking->price_per_night : 5000000;
    $discount      = isset($booking->discount_amount) ? (int) $booking->discount_amount : 0;
    $promoCode     = $booking->promo_code ?? null;
    $email         = $booking->email ?? session('booking.email', '—');
    $phone         = $booking->phone ?? session('booking.phone', '—');
    $guests        = $booking->guests ?? session('booking.guests', '—');

    $ci     = $booking->check_in  ?? session('booking.check_in');
    $co     = $booking->check_out ?? session('booking.check_out');
    $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;
    $base   = $pricePerNight * $nights;

    if ($total === 0) $total = $base - $discount;

    $status = $booking->status ?? 'PENDING';
@endphp

<div class="invoice-container">
    <!-- Step Indicator -->
    <div class="steps">
        <div class="step done">
            <div class="step-circle">✓</div>
            <div class="step-label">Details</div>
        </div>
        <div class="step done">
            <div class="step-circle">✓</div>
            <div class="step-label">Confirm</div>
        </div>
        <div class="step active">
            <div class="step-circle">3</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="step">
            <div class="step-circle">4</div>
            <div class="step-label">Done</div>
        </div>
    </div>

    <!-- Booking Reference Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <div style="font-size: 12px; opacity: 0.9; margin-bottom: 0.5rem;">BOOKING REFERENCE</div>
                <div style="font-size: 28px; font-weight: bold;">{{ $bookingCode }}</div>
            </div>
            <span class="badge-status {{ $status === 'CONFIRMED' ? 'badge-paid' : 'badge-pending' }}">
                @if($status === 'CONFIRMED')
                    <i class="fas fa-check-circle"></i> PAID
                @else
                    <i class="fas fa-hourglass-half"></i> PENDING
                @endif
            </span>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="grid-item">
                    <div class="grid-label">Guest Name</div>
                    <div class="grid-value">{{ $guestName }}</div>
                </div>
                <div class="grid-item">
                    <div class="grid-label">Email</div>
                    <div class="grid-value">{{ $email }}</div>
                </div>
                <div class="grid-item">
                    <div class="grid-label">Phone</div>
                    <div class="grid-value">{{ $phone }}</div>
                </div>
                <div class="grid-item">
                    <div class="grid-label">Number of Guests</div>
                    <div class="grid-value">{{ $guests }} {{ is_numeric($guests) ? 'person' : '' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stay Details Card -->
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <i class="fas fa-calendar"></i> Your Stay
            </div>
            <div class="grid-2">
                <div class="grid-item">
                    <div class="grid-label">Check-in</div>
                    <div class="grid-value">{{ $ci ? date('l, d M Y', strtotime($ci)) : '—' }}</div>
                </div>
                <div class="grid-item">
                    <div class="grid-label">Check-out</div>
                    <div class="grid-value">{{ $co ? date('l, d M Y', strtotime($co)) : '—' }}</div>
                </div>
                <div class="grid-item">
                    <div class="grid-label">Duration</div>
                    <div class="grid-value">{{ $nights }} {{ $nights === 1 ? 'Night' : 'Nights' }}</div>
                </div>
                <div class="grid-item">
                    <div class="grid-label">Rate per Night</div>
                    <div class="grid-value">Rp {{ number_format($pricePerNight, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timer (if pending) -->
    @if($status === 'PENDING')
    <div class="timer-box">
        <div class="timer-label">⏱️ Complete Payment Within</div>
        <div class="timer-display" id="countdown">59:59</div>
        <div class="timer-note">Your booking will be cancelled automatically if time expires</div>
    </div>
    @endif

    <!-- Invoice Card -->
    <div class="card" id="invoice-box">
        <div class="card-body">
            <div class="card-title">
                <i class="fas fa-receipt"></i> Invoice
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Villa Swarna Mandapa
                            @if($nights > 0)
                                <br/>
                                <small style="color: #666;">
                                    {{ date('d M Y', strtotime($ci)) }} → {{ date('d M Y', strtotime($co)) }}
                                    ({{ $nights }} × Rp {{ number_format($pricePerNight, 0, ',', '.') }})
                                </small>
                            @endif
                        </td>
                        <td class="text-right fw-bold">Rp {{ number_format($base, 0, ',', '.') }}</td>
                    </tr>
                    @if($discount > 0)
                    <tr>
                        <td>
                            <i class="fas fa-tag" style="color: #C9A96E; margin-right: 0.5rem;"></i>
                            Discount {{ $promoCode ? '(' . $promoCode . ')' : '' }}
                        </td>
                        <td class="text-right" style="color: #22c55e; font-weight: 600;">
                            -Rp {{ number_format($discount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="summary-row total">
                <span>Total Amount Due</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div style="background: #F3F4F6; padding: 1rem; border-radius: 8px; margin-top: 1.5rem;">
                <small style="color: #666;">
                    <i class="fas fa-info-circle" style="color: #C9A96E; margin-right: 0.5rem;"></i>
                    Non-refundable. Payment must be completed to confirm your booking.
                </small>
            </div>
        </div>
    </div>

    <!-- Payment Card -->
    @if($status !== 'CONFIRMED')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <i class="fas fa-lock"></i> Ready to Pay?
            </div>
            <p style="color: #666; margin-bottom: 1.5rem; font-size: 14px;">
                You'll be redirected to our secure DOKU payment page. Choose your preferred payment method.
            </p>

            <div style="background: #F0F9FF; border-left: 4px solid #3b82f6; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 13px; color: #333;">
                <strong style="color: #1e40af;">Available payment methods:</strong><br/>
                💳 Credit/Debit Card · 🏦 Bank Transfer · 💰 E-Wallet (OVO, DANA, GCash, etc) · 📱 QRIS
            </div>

            <form action="{{ route('payment.create') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="payment_method" value="CREDIT_CARD">
                <button type="submit" class="btn btn-primary w-100" style="width: 100%;">
                    <i class="fas fa-lock"></i> Pay Now — Rp {{ number_format($total, 0, ',', '.') }}
                </button>
            </form>

            <div class="btn-group">
                <button class="btn btn-secondary" onclick="printInvoice(); return false;">
                    <i class="fas fa-download"></i> Download Invoice
                </button>
                <a href="{{ route('booking.confirmation') }}" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Edit Booking
                </a>
            </div>

            <p style="text-align: center; color: #999; font-size: 12px; margin-top: 1rem;">
                🔒 256-bit SSL Encryption · Powered by DOKU
            </p>
        </div>
    </div>
    @else
    <div class="card" style="background: linear-gradient(135deg, #D1FAE5 0%, #ECFDF5 100%); border: 2px solid #22c55e;">
        <div class="card-body">
            <div style="text-align: center;">
                <i class="fas fa-check-circle" style="font-size: 48px; color: #22c55e; margin-bottom: 1rem;"></i>
                <h2 style="color: #065f46; margin-bottom: 0.5rem;">Payment Confirmed!</h2>
                <p style="color: #047857;">Your booking is now confirmed. We'll send you a confirmation email shortly.</p>
                <a href="{{ route('booking.form') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Contact Info -->
    <div class="contact-box">
        <div class="contact-title">
            <i class="fas fa-headset"></i> Need Help?
        </div>
        <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span><a href="tel:+6427297357">+64 27 297 3575</a></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span><a href="mailto:reservations@swarnamandapa.com">reservations@swarnamandapa.com</a></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Jl. Nuansa Angkasa III No.7 & 9, Ungasan, Kec. Kuta Sel., Kabupaten Badung, Bali 80361, Indonesia</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-check payment status
(function () {
    const checkInterval = setInterval(async () => {
        try {
            const resp = await fetch('/api/booking-status/{{ $bookingCode }}', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });
            const data = await resp.json();
            
            if (data.status === 'CONFIRMED') {
                clearInterval(checkInterval);
                window.location.href = '{{ route("booking.status", ["code" => $bookingCode]) }}';
            }
        } catch (e) {
            console.log('Status check error:', e);
        }
    }, 2000);
});

// Countdown timer
function startCountdown() {
    const timerElement = document.getElementById('countdown');
    if (!timerElement) return;

    let totalSeconds = 3600; // 1 hour
    
    const updateTimer = () => {
        const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        
        timerElement.textContent = `${hours}:${minutes}:${seconds}`;
        
        if (totalSeconds > 0) {
            totalSeconds--;
            setTimeout(updateTimer, 1000);
        }
    };
    
    updateTimer();
}

// Print invoice
function printInvoice() {
    window.print();
}

// Start on load
document.addEventListener('DOMContentLoaded', startCountdown);
</script>
@endpush

@endsection
