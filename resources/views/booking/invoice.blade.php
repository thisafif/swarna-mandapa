{{-- resources/views/booking/invoice.blade.php --}}
@extends('layouts.app')
@section('title', 'Invoice & Payment — Swarna Mandapa')

@push('styles')
<style>
    .fade-up { animation: fadeUp .5s ease both }
    @keyframes fadeUp { from { opacity:0; transform:translateY(16px) } to { opacity:1; transform:translateY(0) } }

    .step-wrap { display:flex; align-items:center; justify-content:center; margin-bottom:2.5rem }
    .step-item { display:flex; flex-direction:column; align-items:center; gap:.3rem }
    .step-line { width:60px; height:2px; background:var(--border); margin:0 .25rem; margin-bottom:1.2rem }
    .step-dot { width:30px; height:30px; border-radius:50%; border:2px solid var(--border); background:var(--white); display:grid; place-items:center; font-size:.72rem; font-weight:600; color:var(--text-muted) }
    .step-item.active .step-dot { border-color:var(--gold); background:var(--gold); color:#fff }
    .step-item.done .step-dot { border-color:var(--success); background:var(--success); color:#fff }
    .step-lbl { font-size:.65rem; letter-spacing:.07em; color:var(--text-muted); text-transform:uppercase }
    .step-item.active .step-lbl { color:var(--gold); font-weight:600 }
    .step-item.done .step-lbl { color:var(--success) }

    /* Payment method badges — informational only */
    .pay-methods-info { display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:1.25rem }
    .pay-badge {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .35rem .75rem; border-radius: 50px;
        border: 1px solid var(--border); background: var(--white);
        font-size: .75rem; font-weight: 500; color: var(--text-dark);
    }
    .pay-badge i { color: var(--gold) }

    /* Hide "Book Now" CTA button on invoice page */
    [data-scroll-cta] { display: none !important; }

    /* Mode Print (Download PDF) - A5 Landscape */
@media print {
    @page {
        size: A5 landscape;
        margin: 0.8cm;
    }

    * { 
        margin: 0 !important; 
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    body { 
        background: white !important; 
        width: 100% !important;
    }

    /* Sembunyikan semua KECUALI invoice-box */
    .page-header,
    .step-wrap,
    .btn,
    button,
    .navbar,
    footer,
    .countdown-timer,
    .container > .panel:not(#invoice-box) { 
        display: none !important; 
    }

    /* Sembunyikan container padding */
    .container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Tampilkan HANYA invoice-box */
    #invoice-box {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        background: white !important;
        border: none !important;
        box-shadow: none !important;
        padding: 12mm !important;
        margin: 0 !important;
        border-radius: 0 !important;
        page-break-after: avoid !important;
        position: static !important;
    }

    #invoice-box * {
        color: #000 !important;
        background: transparent !important;
    }
}

    /* Print-friendly text */
    #invoice-box * {
        color: #000 !important;
        background: transparent !important;
    }

    /* Tampilkan invoice content */
    #invoice-box .d-flex,
    #invoice-box .row,
    #invoice-box .table {
        display: block !important;
    }

    #invoice-box .col-6 {
        width: 50% !important;
        display: inline-block !important;
    }

    #invoice-box table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    #invoice-box th,
    #invoice-box td {
        border: 1px solid #ddd !important;
        padding: 8px !important;
        text-align: left !important;
    }
}
</style>
@endpush

@section('content')

<div class="page-header fade-up">
    <span class="section-label">Reservations</span>
    <h1>Book Your <em>Escape</em></h1>
    <p>Complete the form below to reserve your stay.</p>
</div>

<div class="container pb-5" style="max-width:780px">

    {{-- Steps --}}
    <div class="step-wrap">
        <div class="step-item done">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">Details</div>
        </div>
        <div class="step-line" style="background:var(--success)"></div>
        <div class="step-item done">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">Confirm</div>
        </div>
        <div class="step-line" style="background:var(--success)"></div>
        <div class="step-item active">
            <div class="step-dot">3</div>
            <div class="step-lbl">Payment</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">Done</div>
        </div>
    </div>

    @php
    $bookingCode   = $booking->booking_code ?? session('booking_code', 'SWM-' . date('Y') . '-XXXXXX');
    $guestName     = isset($booking->first_name)
                      ? trim($booking->first_name . ' ' . $booking->last_name)
                      : trim(session('booking.first_name','Guest').' '.session('booking.last_name',''));

    $total         = isset($booking->total_price) ? (int) $booking->total_price : 0;
    $pricePerNight = isset($booking->price_per_night) ? (int) $booking->price_per_night : 0;
    $discount      = isset($booking->discount_amount) ? (int) $booking->discount_amount : 0;
    $promoCode     = $booking->promo_code ?? null;

    $ci     = $booking->check_in  ?? session('booking.check_in');
    $co     = $booking->check_out ?? session('booking.check_out');
    $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;
    $base   = $pricePerNight * $nights;

    if ($total === 0) $total = $base - $discount;

    $expiresAt = $booking->expires_at ?? now()->addHour();
    $expiresTs = $expiresAt instanceof \Carbon\Carbon ? $expiresAt->timestamp : strtotime($expiresAt);
@endphp

    {{-- Booking Banner --}}
    <div class="rounded-4 p-4 mb-4 text-white fade-up"
         style="background:linear-gradient(135deg,#C9A96E 0%,#8B6914 100%)">
        <div class="form-label-sm text-white mb-1" style="opacity:.8">Booking Created</div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:600;letter-spacing:.08em">
            {{ $bookingCode }}
        </div>
        <div class="mt-2 d-flex align-items-center gap-2">
            @if(isset($booking->status) && $booking->status === 'CONFIRMED')
                <span class="badge" style="background:#22c55e;color:#fff;border-radius:50px;font-size:.72rem;padding:.3em .9em">
                    <i class="bi bi-check-circle-fill me-1" style="font-size:.5rem;vertical-align:middle"></i>PAID
                </span>
                <span style="font-size:.82rem;opacity:.85">Payment successful. Your stay is confirmed!</span>
            @else
                <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;border-radius:50px;font-size:.72rem;padding:.3em .9em">
                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle"></i>PENDING
                </span>
                <span style="font-size:.82rem;opacity:.85">Awaiting payment</span>
            @endif
        </div>
    </div>

    {{-- Countdown (Hanya tampil jika PENDING) --}}
    @if(!isset($booking->status) || $booking->status === 'PENDING')
    <div class="countdown-timer d-flex align-items-center gap-2 p-3 rounded-3 mb-3 fade-up"
         style="background:#FFF8ED;border:1px solid #F0D9A0;font-size:.82rem">
        <i class="bi bi-clock-history text-warning fs-5 flex-shrink-0"></i>
        <div>
            <span class="fw-600" style="color:#8B6914">Complete payment within: </span>
            <span id="countdown" class="fw-700" style="color:#C9A96E;font-size:1rem;font-family:'Cormorant Garamond',serif">60:00</span>
            <span class="text-muted-sm ms-1">— or your booking will be cancelled automatically.</span>
        </div>
    </div>
    @endif

    {{-- Booking Details --}}
    <div class="panel fade-up">
        <div class="panel-title"><i class="bi bi-info-circle me-2 text-gold"></i>Booking Details</div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-label-sm">Guest Name</div>
                <div class="fw-600">{{ $guestName }}</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Email</div>
                <div class="fw-600" style="word-break:break-all">{{ $booking->email ?? session('booking.email', '—') }}</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Check-in</div>
                <div class="fw-600">{{ $ci ? date('D, d M Y', strtotime($ci)) : '—' }}</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Check-out</div>
                <div class="fw-600">{{ $co ? date('D, d M Y', strtotime($co)) : '—' }}</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Duration</div>
                <div class="fw-600">{{ $nights }} Nights</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Guests</div>
                <div class="fw-600">{{ $booking->guests ?? session('booking.guests', '—') }} Guests</div>
            </div>
            <div class="col-12">
                <div class="form-label-sm">Total Amount</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:600;color:var(--gold)">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

   {{-- Invoice --}}
    <div class="panel fade-up" id="invoice-box">
        @include('booking.invoice-shared', ['booking' => $booking])
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         READY TO PAY
         DOKU Checkout adalah hosted page — semua metode bayar
         ditampilkan di halaman DOKU. Badge di bawah hanya informatif.
    ═══════════════════════════════════════════════════════════ --}}
    <div class="panel fade-up">
        <div class="panel-title"><i class="bi bi-lightning-charge me-2 text-gold"></i>Ready to Pay?</div>

        <p class="text-muted-sm mb-3">
            You'll be redirected to our secure payment page. Choose your preferred method there.
        </p>

        {{-- Accepted methods — informational badges --}}
        <div class="pay-methods-info">
            <span class="pay-badge"><i class="bi bi-credit-card-fill"></i>Credit / Debit Card</span>
            <span class="pay-badge"><i class="bi bi-bank2"></i>Virtual Account</span>
            <span class="pay-badge"><i class="bi bi-wallet2"></i>E-Wallet</span>
            <span class="pay-badge"><i class="bi bi-qr-code"></i>QRIS</span>
        </div>

        <div class="mb-3 p-2 rounded-3 d-flex align-items-center gap-2"
             style="background:var(--cream-dark);border:1px solid var(--border);font-size:.78rem">
            <i class="bi bi-info-circle text-gold flex-shrink-0"></i>
            <span class="text-muted-sm">BCA · BRI · Mandiri · BNI · OVO · DANA · ShopeePay · QRIS · and more</span>
        </div>

        @if ($errors->has('payment'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3 rounded-3" style="font-size:.85rem">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                {{ $errors->first('payment') }}
            </div>
        @endif

        @if(isset($booking->status) && $booking->status === 'CONFIRMED')
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3 rounded-3" style="font-size:.85rem">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                Payment has been completed. Thank you!
            </div>
        @else
            {{-- Form — method field tetap ada untuk validasi controller, default CREDIT_CARD --}}
            <form action="{{ route('payment.create') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="payment_method" value="CREDIT_CARD">
                <button type="submit" class="btn btn-gold btn-gold-lg w-100 mb-3" id="btn-pay">
                    <i class="bi bi-lock-fill me-2"></i> Pay Now — Rp {{ number_format($total, 0, ',', '.') }}
                </button>
            </form>
        @endif

        <a href="{{ route('booking.invoice.pdf', ['code' => $bookingCode]) }}" 
            target="_blank"
            class="btn btn-gold-outline w-100 mb-2">
            <i class="bi bi-download me-2"></i>Download Invoice (PDF)
        </a>

        <p class="text-center text-muted-sm mt-3">
            <i class="bi bi-lock-fill me-1 text-gold"></i>256-bit SSL · Powered by DOKU
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ─── Auto-check booking status (detect payment completion) ────
(function () {
    let lastStatus = '{{ $booking->status ?? 'PENDING' }}';
    const checkInterval = setInterval(async () => {
        try {
            const resp = await fetch('/api/booking-status/{{ $bookingCode }}', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });
            const data = await resp.json();
            
            if (data.status && data.status !== lastStatus) {
                lastStatus = data.status;
                
                if (data.status === 'CONFIRMED') {
                    clearInterval(checkInterval);
                    // Status changed to CONFIRMED! Redirect to status page
                    setTimeout(() => {
                        window.location.href = '/booking/status?code={{ $bookingCode }}';
                    }, 500);
                }
            }
        } catch (e) {
            // Silent fail - network error is ok
        }
    }, 2000); // Check every 2 seconds
    
    // Stop checking after 2 hours
    setTimeout(() => clearInterval(checkInterval), 7200000);
})();

// ─── Print Invoice Function ─────────────────────────────────
function printInvoice() {
    // Trigger print dialog
    window.print();
}

// ─── Cleanup print mode ─────────────────────────────────────
window.addEventListener('beforeprint', function() {
    // Ensure invoice-box is visible
    const invoiceBox = document.getElementById('invoice-box');
    if (invoiceBox) {
        invoiceBox.style.display = 'block';
        invoiceBox.style.visibility = 'visible';
    }
});

window.addEventListener('afterprint', function() {
    // Restore normal view if needed
    const invoiceBox = document.getElementById('invoice-box');
    if (invoiceBox) {
        invoiceBox.style.display = 'block';
    }
});

// ─── Countdown timer ─────────────────────────────────────────
(function () {
    const el = document.getElementById('countdown');
    if (!el) return; // Jika tidak ada countdown (status CONFIRMED)
    
    const expiresAt = {{ $expiresTs }} * 1000;
    const btn = document.getElementById('btn-pay');

    function tick() {
        const diff = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
        const m = String(Math.floor(diff / 60)).padStart(2, '0');
        const s = String(diff % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;

        if (diff === 0 && btn) {
            el.textContent    = 'Expired';
            el.style.color    = '#e53e3e';
            btn.disabled      = true;
            btn.innerHTML     = '<i class="bi bi-x-circle me-2"></i>Payment Expired';
            btn.style.opacity = '.5';
            setTimeout(() => {
                window.location.href = '{{ route('booking.status', ['code' => $bookingCode]) }}';
            }, 3000);
        } else if (diff > 0) {
            setTimeout(tick, 1000);
        }
    }
    tick();
})();

// ─── Prevent double-submit ───────────────────────────────────
const paymentForm = document.getElementById('payment-form');
if (paymentForm) {
    paymentForm.addEventListener('submit', function () {
        const btn = document.getElementById('btn-pay');
        if (btn) {
            btn.disabled  = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Redirecting to payment...';
        }
    });
}
</script>
@endpush
