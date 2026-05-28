{{-- resources/views/booking/status.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking Status — Swarna Mandapa')

@push('styles')
<style>
    .fade-up{animation:fadeUp .55s ease both}
    @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}

    /* Success/Status animations */
    .status-ring{width:80px;height:80px;border-radius:50%;display:grid;place-items:center;margin:0 auto}
    .status-circle{width:60px;height:60px;border-radius:50%;display:grid;place-items:center;animation:popIn .5s cubic-bezier(.34,1.56,.64,1)}
    @keyframes popIn{from{transform:scale(0)}to{transform:scale(1)}}

    /* Timeline */
    .timeline{position:relative;padding-left:2.5rem}
    .timeline::before{content:'';position:absolute;left:13px;top:14px;bottom:14px;width:2px;background:var(--border)}
    .tl-item{position:relative;margin-bottom:1.5rem}
    .tl-item:last-child{margin-bottom:0}
    .tl-dot{position:absolute;left:-2.5rem;top:2px;width:28px;height:28px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:grid;place-items:center;font-size:.65rem;transition:all .3s}
    .tl-item.done .tl-dot{background:var(--success);border-color:var(--success);color:#fff}
    .tl-item.active .tl-dot{background:var(--gold);border-color:var(--gold);color:#fff}
    .tl-item.pending .tl-dot{background:var(--white);border-color:var(--border);color:var(--text-muted)}
    .tl-item.cancelled .tl-dot{background:var(--danger);border-color:var(--danger);color:#fff}
    .tl-title{font-weight:600;font-size:.9rem}
    .tl-desc{font-size:.8rem;color:var(--text-muted)}
    .tl-time{font-size:.72rem;color:var(--text-muted);margin-top:.2rem}

    .step-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:2.5rem}
    .step-item{display:flex;flex-direction:column;align-items:center;gap:.3rem}
    .step-line{width:60px;height:2px;background:var(--border);margin:0 .25rem;margin-bottom:1.2rem}
    .step-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:grid;place-items:center;font-size:.72rem;font-weight:600;color:var(--text-muted)}
    .step-item.done .step-dot{border-color:var(--gold);background:var(--gold);color:#fff}
    .step-lbl{font-size:.65rem;letter-spacing:.07em;color:var(--text-muted);text-transform:uppercase}
    .step-item.done .step-lbl{color:var(--gold);font-weight:600}
</style>
@endpush

@section('content')

@php
    $status = $booking->status ?? 'NOT_FOUND';
    $code = $booking->booking_code ?? '—';
@endphp

@if($status === 'CONFIRMED')
    @include('booking.done-clean')
@else
    <span class="section-label">Reservations</span>
    <h1>Track Your <em>Booking</em></h1>
    <p>Your booking status and details are shown below.</p>
</div>

<div class="container pb-5" style="max-width:760px">

    {{-- ── STEPS ── --}}
    <div class="step-wrap fade-up">
        @foreach(['Details','Confirm','Payment','Done'] as $idx => $s)
        <div class="step-item {{ $status === 'CONFIRMED' && $idx <= 3 ? 'done' : ($status === 'PENDING' && $idx < 3 ? 'done' : '') }}">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">{{ $s }}</div>
        </div>
        @if(!$loop->last)
        <div class="step-line" style="background:{{ ($status === 'CONFIRMED' && $idx < 3) || ($status === 'PENDING' && $idx < 2) ? 'var(--gold)' : 'var(--border)' }}"></div>
        @endif
        @endforeach
    </div>

    {{-- ── DYNAMIC STATUS CARD ── --}}
    <div class="panel mb-4 text-center fade-up" style="position:relative; overflow:hidden; border:none; box-shadow:0 15px 45px rgba(0,0,0,0.05); padding-top:3.5rem; padding-bottom:3.5rem; background:#fff">
        
        @if($status === 'CONFIRMED')
            <div style="position:absolute; top:0; left:0; right:0; height:5px; background:linear-gradient(90deg, var(--gold-pale), var(--gold), var(--gold-pale));"></div>
            <div class="status-ring mb-4" style="background:rgba(34,197,94,0.1); box-shadow:inset 0 0 0 1px rgba(34,197,94,0.25)">
                <div class="status-circle" style="background:#22c55e; box-shadow:0 8px 24px rgba(34,197,94,0.35)">
                    <i class="bi bi-check-lg text-white" style="font-size:1.8rem"></i>
                </div>
            </div>
            <div class="form-label-sm mb-2" style="color:var(--gold); letter-spacing:.15em">RESERVATION CONFIRMED</div>
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.6rem; font-weight:600; color:var(--text-dark); margin-bottom:1rem">
                Your Stay is Secured
            </h2>
            <p class="text-muted-sm" style="max-width:420px; font-size:.9rem; margin:0 auto 2rem; line-height:1.6">
                Booking <strong style="color:var(--gold); font-weight:600">{{ $code }}</strong> has been successfully processed. A receipt has been sent to your email.
            </p>

        @elseif($status === 'PENDING')
            <div style="position:absolute; top:0; left:0; right:0; height:5px; background:linear-gradient(90deg, #fef08a, #eab308, #fef08a);"></div>
            <div class="status-ring mb-4" style="background:rgba(234,179,8,0.1); box-shadow:inset 0 0 0 1px rgba(234,179,8,0.25)">
                <div class="status-circle" style="background:#eab308; box-shadow:0 8px 24px rgba(234,179,8,0.35)">
                    <i class="bi bi-hourglass-split text-white" style="font-size:1.5rem"></i>
                </div>
            </div>
            <div class="form-label-sm mb-2" style="color:#eab308; letter-spacing:.15em">AWAITING PAYMENT</div>
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.6rem; font-weight:600; color:var(--text-dark); margin-bottom:1rem">
                Action Required
            </h2>
            <p class="text-muted-sm" style="max-width:420px; font-size:.9rem; margin:0 auto 2rem; line-height:1.6">
                Your booking <strong style="color:var(--gold); font-weight:600">{{ $code }}</strong> is on hold. Please complete your payment before the timer expires.
            </p>
            <a href="{{ route('booking.invoice') }}" class="btn btn-gold mb-4">Complete Payment Now</a>

        @elseif($status === 'CANCELLED')
            <div style="position:absolute; top:0; left:0; right:0; height:5px; background:linear-gradient(90deg, #fecaca, #ef4444, #fecaca);"></div>
            <div class="status-ring mb-4" style="background:rgba(239,68,68,0.1); box-shadow:inset 0 0 0 1px rgba(239,68,68,0.25)">
                <div class="status-circle" style="background:#ef4444; box-shadow:0 8px 24px rgba(239,68,68,0.35)">
                    <i class="bi bi-x-lg text-white" style="font-size:1.5rem"></i>
                </div>
            </div>
            <div class="form-label-sm mb-2" style="color:#ef4444; letter-spacing:.15em">BOOKING CANCELLED</div>
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.6rem; font-weight:600; color:var(--text-dark); margin-bottom:1rem">
                Payment Expired
            </h2>
            <p class="text-muted-sm" style="max-width:420px; font-size:.9rem; margin:0 auto 2rem; line-height:1.6">
                Your booking <strong style="color:var(--gold); font-weight:600">{{ $code }}</strong> was cancelled because the payment window has closed.
            </p>
            <a href="{{ route('booking.form') }}" class="btn btn-gold-outline mb-4">Make a New Booking</a>
        @else
            <div class="form-label-sm mb-2 text-danger">BOOKING NOT FOUND</div>
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:2rem; margin-bottom:1rem">Invalid Booking Code</h2>
            <a href="{{ route('booking.form') }}" class="btn btn-gold-outline">Return to Booking</a>
        @endif
        
        @if($booking)
        <div class="d-inline-flex align-items-center gap-3 px-4 py-3 rounded-pill"
             style="background:var(--cream); border:1px solid rgba(184,146,74,0.2)">
            <i class="bi bi-bookmark-star-fill text-gold" style="font-size:1.2rem"></i>
            <div class="d-flex flex-column align-items-start text-start" style="line-height:1.2">
                <span style="font-size:.65rem; letter-spacing:.1em; color:var(--text-muted); text-transform:uppercase">Booking Reference</span>
                <span class="fw-700" style="font-size:1.1rem; letter-spacing:.05em; color:var(--text-dark)">
                    {{ $code }}
                </span>
            </div>
        </div>
        @endif
    </div>

    @if($booking)
    {{-- ── BOOKING PROGRESS ── --}}
    <div class="panel fade-up">
        <div class="panel-title"><i class="bi bi-activity me-2 text-gold"></i>Booking Progress</div>
        <div class="timeline">

            <div class="tl-item done">
                <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                <div class="tl-title">Booking Submitted</div>
                <div class="tl-desc">Your booking request has been received.</div>
                <div class="tl-time">{{ $booking->created_at->format('d M Y, H:i') }}</div>
            </div>

            @if($status === 'CONFIRMED')
                <div class="tl-item done">
                    <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                    <div class="tl-title">Payment Received</div>
                    <div class="tl-desc">Your payment has been verified successfully.</div>
                    <div class="tl-time">{{ $booking->paid_at ? \Carbon\Carbon::parse($booking->paid_at)->format('d M Y, H:i') : 'Done' }}</div>
                </div>
                <div class="tl-item done">
                    <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                    <div class="tl-title">Booking Confirmed</div>
                    <div class="tl-desc">Your stay at Villa Swarna Mandapa is officially secured.</div>
                </div>
                <div class="tl-item pending">
                    <div class="tl-dot"><i class="bi bi-key"></i></div>
                    <div class="tl-title">Check-in</div>
                    <div class="tl-desc" style="color:var(--text-muted)">Arriving soon — from 15:00 on {{ date('d M Y', strtotime($booking->check_in)) }}.</div>
                </div>
            @elseif($status === 'PENDING')
                <div class="tl-item active">
                    <div class="tl-dot"><i class="bi bi-hourglass-split"></i></div>
                    <div class="tl-title" style="color:var(--gold)">Awaiting Payment</div>
                    <div class="tl-desc">Please complete the payment before the timer expires.</div>
                </div>
            @elseif($status === 'CANCELLED')
                <div class="tl-item cancelled">
                    <div class="tl-dot"><i class="bi bi-x-lg"></i></div>
                    <div class="tl-title text-danger">Cancelled</div>
                    <div class="tl-desc">Booking was cancelled automatically due to unpaid invoice.</div>
                </div>
            @endif

        </div>
    </div>

    {{-- ── STAY DETAILS ── --}}
    <div class="panel fade-up">
        <div class="panel-title"><i class="bi bi-calendar-event me-2 text-gold"></i>Stay Details</div>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Check-in</div>
                <div class="fw-600">{{ date('d M Y', strtotime($booking->check_in)) }}</div>
                <div class="text-muted-sm">15:00</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Check-out</div>
                <div class="fw-600">{{ date('d M Y', strtotime($booking->check_out)) }}</div>
                <div class="text-muted-sm">11:00</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Duration</div>
                @php
                    $nights = (new DateTime($booking->check_in))->diff(new DateTime($booking->check_out))->days;
                @endphp
                <div class="fw-600">{{ $nights }} Nights</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Guests</div>
                <div class="fw-600">{{ $booking->guests }} Guests</div>
            </div>
        </div>
    </div>

    {{-- ── INVOICE ── --}}
<div class="panel fade-up" id="invoice-box">
    @include('booking.invoice-shared', ['booking' => $booking])

    @if($status === 'CONFIRMED')
    <div class="d-flex gap-3 flex-wrap mt-4">
        <a href="{{ route('booking.invoice.pdf') }}?code={{ $booking->booking_code }}" 
           target="_blank"
           class="btn btn-gold-outline flex-grow-1">
            <i class="bi bi-download me-2"></i>Download Receipt
        </a>
        <a href="{{ route('booking.form') }}" class="btn btn-gold-outline flex-grow-1">
            <i class="bi bi-plus-circle me-2"></i>Make Another Booking
        </a>
    </div>
    @endif
</div>
    @endif

</div>

@endsection

@push('scripts')
<script>
// Kalau mau cari booking lain, nanti kamu bisa sambungkan ini ke route pencarian
function searchBooking(){
    const c=document.getElementById('search-code').value.trim();
    if(!c){ alert('Please enter a booking code.'); return; }
    window.location.href = '/status?code=' + c;
}
</script>
@endpush