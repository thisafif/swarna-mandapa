{{-- resources/views/booking/invoice.blade.php --}}
@extends('layouts.app')
@section('title', 'Invoice & Payment - Swarna Mandapa')

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

    /* ── Print Styles ── */
    @media print {
        /* Hide Site Footer and Sidebar Navigation */
        footer, [data-nav-panel] { display: none !important; }
        
        /* Hide Header buttons/menus, keep only the Logo */
        header button, header a[data-scroll-cta] { display: none !important; }
        header { 
            position: relative !important; 
            background: transparent !important; 
            box-shadow: none !important; 
            border: none !important;
            min-height: auto !important;
            padding: 10px 0 !important;
        }

        /* Hide everything except the invoice panel */
        .page-header, .step-wrap, .container > div:not(.invoice-print-panel) { 
            display: none !important; 
        }
        
        /* Ensure the invoice panel shows up properly */
        .invoice-print-panel { 
            display: block !important; 
            box-shadow: none !important; 
            border: 1px solid #ddd !important; 
            padding: 15px !important;
            margin-top: 15px !important;
            page-break-inside: avoid;
        }

        /* Clean up layout for print */
        body { background: #fff !important; padding: 0 !important; }
        .container { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .table td { padding: 6px 0 !important; }
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
        $bookingCode = $booking->booking_code ?? session('booking_code', 'SWM-' . date('Y') . '-XXXXXX');
        $guestName   = isset($booking->first_name)
                        ? trim($booking->first_name . ' ' . $booking->last_name)
                        : trim(session('booking.first_name', 'Guest') . ' ' . session('booking.last_name', ''));

        $base = isset($booking->total_price) ? (float) $booking->total_price : 0;
        if ($base === 0.0) {
            $ci = session('booking.check_in'); $co = session('booking.check_out');
            $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;
            $base = 5000000 * $nights;
        }
        $tax   = (int) round($base * 0.11);
        $fee   = (int) round($base * 0.10);
        $total = (int) ($base + $tax + $fee);

        $ci     = $booking->check_in  ?? session('booking.check_in');
        $co     = $booking->check_out ?? session('booking.check_out');
        $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;

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
            <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;border-radius:50px;font-size:.72rem;padding:.3em .9em">
                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle"></i>PENDING
            </span>
            <span style="font-size:.82rem;opacity:.85">Awaiting payment</span>
        </div>
    </div>

    {{-- Countdown --}}
    <div class="d-flex align-items-center gap-2 p-3 rounded-3 mb-3 fade-up"
         style="background:#FFF8ED;border:1px solid #F0D9A0;font-size:.82rem">
        <i class="bi bi-clock-history text-warning fs-5 flex-shrink-0"></i>
        <div>
            <span class="fw-600" style="color:#8B6914">Complete payment within: </span>
            <span id="countdown" class="fw-700" style="color:#C9A96E;font-size:1rem;font-family:'Cormorant Garamond',serif">60:00</span>
            <span class="text-muted-sm ms-1">— or your booking will be cancelled automatically.</span>
        </div>
    </div>

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
    <div class="panel fade-up invoice-print-panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="form-label-sm">Invoice</div>
                <div class="fw-700" style="font-size:1rem">
                    {{ $booking->payment_order_id ?? 'INV-' . strtoupper(substr(md5($bookingCode), 0, 8)) }}
                </div>
            </div>
            <span class="badge badge-pending">Awaiting Payment</span>
        </div>

        <div class="row g-2 mb-3" style="font-size:.85rem">
            <div class="col-6"><div class="form-label-sm">Booking Code</div><div class="fw-600">{{ $bookingCode }}</div></div>
            <div class="col-6"><div class="form-label-sm">Invoice Date</div><div class="fw-600">{{ date('d M Y') }}</div></div>
            <div class="col-6"><div class="form-label-sm">Billed To</div><div class="fw-600">{{ $guestName }}</div></div>
            <div class="col-6"><div class="form-label-sm">Property</div><div class="fw-600">Villa Swarna Mandapa</div></div>
        </div>

        <table class="table table-borderless mb-0" style="font-size:.88rem">
            <thead><tr style="border-bottom:1px solid var(--border)">
                <th class="form-label-sm ps-0">Description</th>
                <th class="form-label-sm text-end pe-0">Amount</th>
            </tr></thead>
            <tbody>
                <tr>
                    <td class="ps-0 py-2">
                        Villa Superior
                        @if($nights > 0)
                            — {{ date('d M Y', strtotime($ci)) }} → {{ date('d M Y', strtotime($co)) }} ({{ $nights }} nights)
                        @endif
                    </td>
                    <td class="text-end pe-0 py-2 fw-500">Rp {{ number_format($base, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="ps-0 py-2 text-muted-sm">Government Tax (11%)</td>
                    <td class="text-end pe-0 py-2 text-muted-sm">Rp {{ number_format($tax, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="ps-0 py-2 text-muted-sm">Service Fee (10%)</td>
                    <td class="text-end pe-0 py-2 text-muted-sm">Rp {{ number_format($fee, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid var(--border)">
                    <td class="ps-0 pt-3 fw-700" style="font-family:'Cormorant Garamond',serif;font-size:1rem">Total Amount Due</td>
                    <td class="text-end pe-0 pt-3" style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:600;color:var(--gold)">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-3 pt-3 d-flex align-items-center gap-2" style="border-top:1px solid var(--border);font-size:.8rem">
            <i class="bi bi-exclamation-triangle text-gold flex-shrink-0"></i>
            <span class="text-muted-sm">Non-refundable. Complete payment to confirm your booking.</span>
        </div>
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

        {{-- Form — method field tetap ada untuk validasi controller, default CREDIT_CARD --}}
        <form action="{{ route('payment.create') }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="payment_method" value="CREDIT_CARD">

            <button type="submit" class="btn btn-gold btn-gold-lg w-100 mb-3" id="btn-pay">
                <i class="bi bi-lock-fill me-2"></i>
                Pay Now — Rp {{ number_format($total, 0, ',', '.') }}
            </button>
        </form>

        <button class="btn btn-gold-outline w-100 mb-2" onclick="window.print(); return false;">
            <i class="bi bi-printer me-2"></i> Print / Download PDF
        </button>

        <p class="text-center text-muted-sm mt-3">
            <i class="bi bi-lock-fill me-1 text-gold"></i>256-bit SSL · Powered by DOKU
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ─── Countdown timer ─────────────────────────────────────────
(function () {
    const expiresAt = {{ $expiresTs }} * 1000;
    const el  = document.getElementById('countdown');
    const btn = document.getElementById('btn-pay');

    function tick() {
        const diff = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
        const m = String(Math.floor(diff / 60)).padStart(2, '0');
        const s = String(diff % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;

        if (diff === 0) {
            el.textContent    = 'Expired';
            el.style.color    = '#e53e3e';
            btn.disabled      = true;
            btn.innerHTML     = '<i class="bi bi-x-circle me-2"></i>Payment Expired';
            btn.style.opacity = '.5';
            setTimeout(() => {
                window.location.href = '{{ route('booking.status', ['code' => $bookingCode]) }}';
            }, 3000);
        } else {
            setTimeout(tick, 1000);
        }
    }
    tick();
})();

// ─── Prevent double-submit ───────────────────────────────────
document.getElementById('payment-form').addEventListener('submit', function () {
    const btn = document.getElementById('btn-pay');
    btn.disabled  = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Redirecting to payment...';
});
</script>
@endpush