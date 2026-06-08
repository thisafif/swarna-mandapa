{{-- resources/views/booking/done.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking Confirmed — Swarna Mandapa')

@push('styles')
<style>
    .fade-up { animation: fadeUp .6s ease both }
    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .fade-up:nth-child(1){animation-delay:.05s}
    .fade-up:nth-child(2){animation-delay:.15s}
    .fade-up:nth-child(3){animation-delay:.25s}

    .done-hero {
        background: linear-gradient(135deg, #C9A96E 0%, #8B6914 100%);
        border-radius: var(--radius-lg, 16px);
        padding: 3rem 2rem;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .done-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
    }
    .done-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -40px;
        width: 140px; height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }

    .check-ring {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,.15);
        display: grid; place-items: center;
        margin: 0 auto 1.5rem;
        animation: popIn .5s cubic-bezier(.34,1.56,.64,1) .1s both;
    }
    @keyframes popIn { from{transform:scale(0)} to{transform:scale(1)} }

    .booking-ref {
        display: inline-block;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 50px;
        padding: .45rem 1.5rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: .1em;
        margin-top: 1rem;
    }

    /* Invoice print area */
    .invoice-print-area {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2rem;
        max-width: 520px;
        margin: 0 auto;
    }

    .inv-logo {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--gold);
        letter-spacing: .08em;
    }

    .inv-badge {
        display: inline-block;
        background: #22c55e;
        color: #fff;
        border-radius: 50px;
        font-size: .68rem;
        font-weight: 600;
        padding: .25em .85em;
        letter-spacing: .05em;
    }

    .inv-table { width: 100%; font-size: .85rem; border-collapse: collapse }
    .inv-table td { padding: .45rem 0; vertical-align: top }
    .inv-table .inv-sep { border-top: 1px solid var(--border); padding-top: .6rem }
    .inv-table .inv-total td { border-top: 2px solid var(--border); padding-top: .75rem; font-weight: 700 }
    .inv-total .inv-amount {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        color: var(--gold);
        text-align: right;
    }
    .inv-label { color: var(--text-muted); font-size: .72rem; text-transform: uppercase; letter-spacing: .08em }

    /* Print styles — hanya cetak invoice-print-area */
    @media print {
        body * { visibility: hidden !important }
        .invoice-print-area,
        .invoice-print-area * { visibility: visible !important }
        .invoice-print-area {
            position: fixed !important;
            top: 20px; left: 50%;
            transform: translateX(-50%);
            width: 520px;
            border: none !important;
            box-shadow: none !important;
            padding: 1.5rem !important;
        }
        .no-print { display: none !important }
    }

    .step-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:2.5rem}
    .step-item{display:flex;flex-direction:column;align-items:center;gap:.3rem}
    .step-line{width:60px;height:2px;margin:0 .25rem;margin-bottom:1.2rem}
    .step-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:grid;place-items:center;font-size:.72rem;font-weight:600;color:var(--text-muted)}
    .step-item.done .step-dot{border-color:var(--gold);background:var(--gold);color:#fff}
    .step-lbl{font-size:.65rem;letter-spacing:.07em;color:var(--text-muted);text-transform:uppercase}
    .step-item.done .step-lbl{color:var(--gold);font-weight:600}
</style>
@endpush

@section('content')

@php
    $guestName     = trim($booking->first_name . ' ' . $booking->last_name);
    $ci            = $booking->check_in;
    $co            = $booking->check_out;
    $nights        = (new DateTime($ci))->diff(new DateTime($co))->days;
    $pricePerNight = (int) $booking->price_per_night;
    $discount      = (float) $booking->discount_amount;
    $total         = (int) $booking->total_price;
    $nightlyBreakdown = $booking->nightly_price_breakdown ?? [];
    $base = $nightlyBreakdown
        ? collect($nightlyBreakdown)->sum(fn ($night) => (int) ($night['price'] ?? 0))
        : $pricePerNight * $nights;
    if ($total > 0 && $base === 0) {
        $base = $total + $discount;
    }
    $promoCode     = $booking->promo_code;
    $orderId       = $booking->payment_order_id ?? ('INV-' . strtoupper(substr(md5($booking->booking_code), 0, 8)));
    $paidAt        = $booking->paid_at ? \Carbon\Carbon::parse($booking->paid_at)->format('d M Y, H:i') : date('d M Y, H:i');
@endphp

<div class="container pb-5" style="max-width: 680px">

    {{-- Steps --}}
    <div class="step-wrap mt-4">
        @foreach(['Details','Confirm','Payment','Done'] as $s)
        <div class="step-item done">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">{{ $s }}</div>
        </div>
        @if(!$loop->last)
        <div class="step-line" style="background:var(--gold)"></div>
        @endif
        @endforeach
    </div>

    {{-- Hero --}}
    <div class="done-hero fade-up mb-4">
        <div class="check-ring">
            <i class="bi bi-check-lg" style="font-size:2rem"></i>
        </div>
        <div style="font-size:.72rem;letter-spacing:.2em;opacity:.8;text-transform:uppercase;margin-bottom:.5rem">
            Booking Confirmed
        </div>
        <h1 style="font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:600;margin-bottom:.5rem">
            Thank You, {{ explode(' ', $guestName)[0] }}!
        </h1>
        <p style="opacity:.85;font-size:.9rem;max-width:380px;margin:0 auto">
            Your stay at Villa Swarna Mandapa has been secured. A confirmation has been sent to
            <strong>{{ $booking->email }}</strong>.
        </p>
        <div class="booking-ref">{{ $booking->booking_code }}</div>
    </div>

    {{-- Invoice --}}
    <div class="panel fade-up" id="invoice-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <div class="panel-title mb-0"><i class="bi bi-receipt me-2 text-gold"></i>Your Receipt</div>
            <a href="{{ route('booking.invoice.pdf') }}?code={{ $booking->booking_code }}" 
             target="_blank"
                class="btn btn-gold-outline btn-sm no-print">
             <i class="bi bi-download me-1"></i>Download PDF
            </a>
        </div>

        <div class="invoice-print-area">
            {{-- Invoice Header --}}
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="inv-logo">SWARNA MANDAPA</div>
                    <div style="font-size:.72rem;color:var(--text-muted)">Jl. Nuansa Angkasa III No 7, Ubud, Bali</div>
                </div>
                <div class="text-end">
                    <span class="inv-badge">PAID</span>
                    <div style="font-size:.72rem;color:var(--text-muted);margin-top:.35rem">{{ $paidAt }}</div>
                </div>
            </div>

            <div style="border-top:2px solid var(--gold);margin-bottom:1rem;padding-top:1rem">
                <div class="row g-2" style="font-size:.82rem">
                    <div class="col-6">
                        <div class="inv-label">Invoice No.</div>
                        <div class="fw-600">{{ $orderId }}</div>
                    </div>
                    <div class="col-6">
                        <div class="inv-label">Booking Code</div>
                        <div class="fw-600">{{ $booking->booking_code }}</div>
                    </div>
                    <div class="col-6">
                        <div class="inv-label">Guest</div>
                        <div class="fw-600">{{ $guestName }}</div>
                    </div>
                    <div class="col-6">
                        <div class="inv-label">Email</div>
                        <div class="fw-600" style="word-break:break-all">{{ $booking->email }}</div>
                    </div>
                </div>
            </div>

            <table class="inv-table">
                <tbody>
                    <tr>
                        <td style="color:var(--text-muted);font-size:.78rem" colspan="2" class="pb-1">DESCRIPTION</td>
                        <td style="color:var(--text-muted);font-size:.78rem;text-align:right" class="pb-1">AMOUNT</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Villa Superior<br>
                            <span style="font-size:.78rem;color:var(--text-muted)">
                                {{ date('d M Y', strtotime($ci)) }} → {{ date('d M Y', strtotime($co)) }}
                                ({{ $nights }} nights × Rp {{ number_format($pricePerNight, 0, ',', '.') }})
                            </span>
                        </td>
                        <td style="text-align:right;vertical-align:middle">
                            Rp {{ number_format($base, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($discount > 0)
                    <tr>
                        <td colspan="2" style="color:#22c55e;font-size:.82rem">
                            <i class="bi bi-tag me-1"></i>Promo Discount{{ $promoCode ? ' ('.$promoCode.')' : '' }}
                        </td>
                        <td style="text-align:right;color:#22c55e">
                            — Rp {{ number_format($discount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    <tr class="inv-total">
                        <td colspan="2" style="font-family:'Cormorant Garamond',serif;font-size:1rem">Total Paid</td>
                        <td class="inv-amount">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top:1.5rem;padding:1rem;background:var(--cream, #faf7f2);border-radius:8px;font-size:.78rem">
                <div style="font-weight:600;color:var(--gold);margin-bottom:.35rem">
                    <i class="bi bi-calendar-check me-1"></i>Stay Details
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem">
                    <div><span style="color:var(--text-muted)">Check-in:</span> {{ date('D, d M Y', strtotime($ci)) }} · 15:00</div>
                    <div><span style="color:var(--text-muted)">Check-out:</span> {{ date('D, d M Y', strtotime($co)) }} · 11:00</div>
                    <div><span style="color:var(--text-muted)">Duration:</span> {{ $nights }} Nights</div>
                    <div><span style="color:var(--text-muted)">Guests:</span> {{ $booking->guests }}</div>
                </div>
            </div>

            <div style="margin-top:1rem;text-align:center;font-size:.72rem;color:var(--text-muted)">
                Thank you for choosing Swarna Mandapa · swarna-mandapa.com
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="panel fade-up text-center">
        <i class="bi bi-stars text-gold" style="font-size:1.5rem"></i>
        <p class="mt-2 mb-3" style="font-size:.88rem;color:var(--text-muted)">
            We look forward to welcoming you. If you have any questions, feel free to contact us.
        </p>
        <a href="{{ route('booking.form') }}" class="btn btn-gold-outline">
            <i class="bi bi-house me-2"></i>Back to Home
        </a>
    </div>

</div>

@endsection
