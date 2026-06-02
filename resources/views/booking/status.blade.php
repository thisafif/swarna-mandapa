{{-- resources/views/booking/status.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking Status - Swarna Mandapa')

@push('styles')
<style>
    .fade-up{animation:fadeUp .55s ease both}
    @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}

    /* Success checkmark animation */
    .success-ring{width:80px;height:80px;border-radius:50%;background:rgba(74,124,89,.1);display:grid;place-items:center;margin:0 auto}
    .success-circle{width:60px;height:60px;border-radius:50%;background:var(--success);display:grid;place-items:center;animation:popIn .5s cubic-bezier(.34,1.56,.64,1)}
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
    .tl-title{font-weight:600;font-size:.9rem}
    .tl-desc{font-size:.8rem;color:var(--text-muted)}
    .tl-time{font-size:.72rem;color:var(--text-muted);margin-top:.2rem}

    /* Search --*/
    .booking-search-wrap{max-width:540px;margin:0 auto 2rem}

    .step-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:2.5rem}
    .step-item{display:flex;flex-direction:column;align-items:center;gap:.3rem}
    .step-line{width:60px;height:2px;background:var(--border);margin:0 .25rem;margin-bottom:1.2rem}
    .step-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:grid;place-items:center;font-size:.72rem;font-weight:600;color:var(--text-muted)}
    .step-item.done .step-dot{border-color:var(--gold);background:var(--gold);color:#fff}
    .step-lbl{font-size:.65rem;letter-spacing:.07em;color:var(--text-muted);text-transform:uppercase}
    .step-item.done .step-lbl{color:var(--gold);font-weight:600}

    /* ── Print Styles ── */
    @media print {
        /* Hide Site Footer */
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

        /* Hide interactive elements and specific panels */
        .print-hide, .btn { display: none !important; }
        
        /* Clean up layout for print */
        body { background: #fff !important; padding: 0 !important; }
        
        .container { 
            max-width: 100% !important; 
            padding: 0 !important; 
            margin: 0 !important;
        }
        
        .panel { 
            box-shadow: none !important; 
            border: 1px solid #ddd !important; 
            page-break-inside: avoid;
            margin-bottom: 15px !important;
            padding: 15px !important;
        }
        
        /* Compress the large Success Card */
        .panel.text-center {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
        
        .success-ring { width: 50px !important; height: 50px !important; margin-bottom: 10px !important; }
        .success-circle { width: 35px !important; height: 35px !important; }
        .success-circle i { font-size: 1.2rem !important; }
        h2 { font-size: 1.7rem !important; margin-bottom: 5px !important; }
        p.text-muted-sm { margin-bottom: 15px !important; font-size: 0.8rem !important; }
        
        /* Compress table padding */
        .table td { padding: 4px 0 !important; }
    }
</style>
@endpush

@section('content')

<div class="page-header fade-up print-hide">
    <span class="section-label">Reservations</span>
    <h1>Track Your <em>Booking</em></h1>
    <p>Your booking status and details are shown below.</p>
</div>

<div class="container pb-5" style="max-width:760px">

    {{-- Steps — All Done --}}
    <div class="step-wrap print-hide">
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

    {{-- ── SUCCESS CARD ── --}}
    <div class="panel mb-4 text-center fade-up" style="position:relative; overflow:hidden; border:none; box-shadow:0 15px 45px rgba(184,146,74,0.12); padding-top:3.5rem; padding-bottom:3.5rem; background:#fff">
        <div style="position:absolute; top:0; left:0; right:0; height:5px; background:linear-gradient(90deg, var(--gold-pale), var(--gold), var(--gold-pale));"></div>
        
        <div class="success-ring mb-4" style="background:var(--gold-pale); width:90px; height:90px; margin:0 auto; box-shadow:inset 0 0 0 1px rgba(184,146,74,0.25)">
            <div class="success-circle" style="background:var(--gold); width:65px; height:65px; box-shadow:0 8px 24px rgba(184,146,74,0.35)">
                <i class="bi bi-check-lg text-white" style="font-size:1.8rem"></i>
            </div>
        </div>
        
        <div class="form-label-sm mb-2" style="color:var(--gold); letter-spacing:.15em">RESERVATION CONFIRMED</div>
        <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.6rem; font-weight:600; color:var(--text-dark); margin-bottom:1rem">
            Your Stay is Secured
        </h2>
        <p class="text-muted-sm" style="max-width:420px; font-size:.9rem; margin:0 auto 2rem; line-height:1.6">
            Booking <strong style="color:var(--gold); font-weight:600">SWM-{{ date('Y') }}-000101</strong> has been successfully processed. An official itinerary has been enclosed to your email.
        </p>
        
        <div class="d-inline-flex align-items-center gap-3 px-4 py-3 rounded-pill"
             style="background:var(--cream); border:1px solid rgba(184,146,74,0.2)">
            <i class="bi bi-bookmark-star-fill text-gold" style="font-size:1.2rem"></i>
            <div class="d-flex flex-column align-items-start text-start" style="line-height:1.2">
                <span style="font-size:.65rem; letter-spacing:.1em; color:var(--text-muted); text-transform:uppercase">Booking Reference</span>
                <span class="fw-700" style="font-size:1.1rem; letter-spacing:.05em; color:var(--text-dark)">
                    SWM-{{ date('Y') }}-000101
                </span>
            </div>
        </div>
    </div>

    {{-- ── SEARCH ── --}}
    <div class="panel fade-up print-hide">
        <div class="panel-title"><i class="bi bi-search me-2 text-gold"></i>Find Another Booking</div>
        <div class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label-sm">Booking Code</label>
                <input type="text" id="search-code" class="form-control form-control-swarna"
                       placeholder="e.g. SWM-2026-000101">
            </div>
            <div class="col-md-4">
                <button class="btn btn-gold w-100" onclick="searchBooking()">
                    <i class="bi bi-search me-2"></i>Search
                </button>
            </div>
        </div>
    </div>

    {{-- ── BOOKING PROGRESS ── --}}
    <div class="panel fade-up print-hide">
        <div class="panel-title"><i class="bi bi-activity me-2 text-gold"></i>Booking Progress</div>
        <div class="timeline">

            <div class="tl-item done">
                <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                <div class="tl-title">Booking Submitted</div>
                <div class="tl-desc">Your booking request has been received.</div>
                <div class="tl-time">{{ date('d M Y, H:i') }}</div>
            </div>

            <div class="tl-item done">
                <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                <div class="tl-title">Payment Received</div>
                <div class="tl-desc">Your payment has been verified successfully.</div>
                <div class="tl-time">{{ date('d M Y, H:i') }}</div>
            </div>

            <div class="tl-item done">
                <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                <div class="tl-title">Booking Confirmed</div>
                <div class="tl-desc">Your stay at Villa Swarna Mandapa is officially secured.</div>
                <div class="tl-time">{{ date('d M Y, H:i') }}</div>
            </div>

            <div class="tl-item pending">
                <div class="tl-dot"><i class="bi bi-key"></i></div>
                <div class="tl-title">Check-in</div>
                <div class="tl-desc" style="color:var(--text-muted)">Arriving soon — from 15:00 on your check-in date.</div>
            </div>

        </div>
    </div>

    {{-- ── STAY DETAILS ── --}}
    <div class="panel fade-up">
        <div class="panel-title"><i class="bi bi-calendar-event me-2 text-gold"></i>Stay Details</div>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Check-in</div>
                <div class="fw-600">{{ isset($booking['check_in']) ? date('d M Y', strtotime($booking['check_in'])) : '—' }}</div>
                <div class="text-muted-sm">15:00</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Check-out</div>
                <div class="fw-600">{{ isset($booking['check_out']) ? date('d M Y', strtotime($booking['check_out'])) : '—' }}</div>
                <div class="text-muted-sm">11:00</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Duration</div>
                @php
                    $nights=0;
                    if(!empty($booking['check_in'])&&!empty($booking['check_out']))
                        $nights=(new DateTime($booking['check_in']))->diff(new DateTime($booking['check_out']))->days;
                @endphp
                <div class="fw-600">{{ $nights ?: '—' }} Nights</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-label-sm">Guests</div>
                <div class="fw-600">{{ $booking['guests'] ?? '—' }} Guests</div>
            </div>
        </div>
    </div>

    {{-- ── INVOICE ── --}}
    <div class="panel fade-up">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="panel-title mb-0"><i class="bi bi-receipt me-2 text-gold"></i>Invoice</div>
            <span class="badge badge-confirmed">Paid</span>
        </div>
        @php
            $base  = 4500000 * $nights;
            $tax   = round($base * 0.11);
            $fee   = round($base * 0.10);
            $total = $base + $tax + $fee;
        @endphp
        <table class="table table-borderless mb-3" style="font-size:.88rem">
            <tbody>
                <tr style="border-bottom:1px solid var(--border)">
                    <td class="ps-0">Villa Superior ({{ $nights }} nights)</td>
                    <td class="text-end pe-0 fw-500">Rp {{ number_format($base,0,',','.') }}</td>
                </tr>
                <tr><td class="ps-0 text-muted-sm">Government Tax (11%)</td><td class="text-end pe-0 text-muted-sm">Rp {{ number_format($tax,0,',','.') }}</td></tr>
                <tr><td class="ps-0 text-muted-sm">Service Fee (10%)</td><td class="text-end pe-0 text-muted-sm">Rp {{ number_format($fee,0,',','.') }}</td></tr>
                <tr style="border-top:2px solid var(--border)">
                    <td class="ps-0 pt-3 fw-700" style="font-family:'Cormorant Garamond',serif">Total Paid</td>
                    <td class="text-end pe-0 pt-3" style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:600;color:var(--success)">
                        Rp {{ number_format($total,0,',','.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex gap-3 flex-wrap">
            <a href="#" class="btn btn-gold-outline flex-grow-1" onclick="window.print()">
                <i class="bi bi-download me-2"></i>Download Invoice
            </a>
            <a href="{{ route('booking.form') }}" class="btn btn-gold-outline flex-grow-1">
                <i class="bi bi-plus-circle me-2"></i>Make Another Booking
            </a>
        </div>
    </div>

    {{-- Removed Email Notifications panel as requested --}}
</div>

@endsection

@push('scripts')
<script>
function searchBooking(){
    const c=document.getElementById('search-code').value.trim();
    if(!c){ alert('Please enter a booking code.'); return; }
    // In production: AJAX to lookup booking
    alert('Searching for: '+c+'\n\n(Connect to backend to enable live lookup)');
}
</script>
@endpush