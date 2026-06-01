{{-- resources/views/booking/confirmation.blade.php --}}
@extends('layouts.app')
@section('title', 'Confirm Booking - Swarna Mandapa')

@push('styles')
<style>
    .fade-up{animation:fadeUp .5s ease both}
    @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
    .detail-row{display:flex;justify-content:space-between;align-items:flex-start;padding:.6rem 0;border-bottom:1px solid var(--border);font-size:.88rem}
    .detail-row:last-child{border-bottom:none}
    .detail-label{color:var(--text-muted);font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;min-width:120px}
    .step-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:2.5rem}
    .step-item{display:flex;flex-direction:column;align-items:center;gap:.3rem}
    .step-line{width:60px;height:2px;background:var(--border);margin:0 .25rem;margin-bottom:1.2rem}
    .step-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:grid;place-items:center;font-size:.72rem;font-weight:600;color:var(--text-muted);transition:all .3s}
    .step-item.active .step-dot{border-color:var(--gold);background:var(--gold);color:#fff}
    .step-item.done .step-dot{border-color:var(--success);background:var(--success);color:#fff}
    .step-lbl{font-size:.65rem;letter-spacing:.07em;color:var(--text-muted);text-transform:uppercase}
    .step-item.active .step-lbl{color:var(--gold);font-weight:600}
    .step-item.done .step-lbl{color:var(--success)}
</style>
@endpush

@section('content')

<div class="page-header fade-up">
    <span class="section-label">Reservations</span>
    <h1>Review & <em>Confirm</em></h1>
    <p>Please review your booking details before proceeding to payment.</p>
</div>

<div class="container pb-5">

    {{-- Steps --}}
    <div class="step-wrap">
        <div class="step-item done">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">Details</div>
        </div>
        <div class="step-line" style="background:var(--success)"></div>
        <div class="step-item active">
            <div class="step-dot">2</div>
            <div class="step-lbl">Confirm</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
            <div class="step-dot">3</div>
            <div class="step-lbl">Payment</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">Done</div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">

            {{-- Stay Summary --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-calendar-check me-2 text-gold"></i>Booking Summary</div>

                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:var(--cream)">
                    <img src="{{ asset('images/Master Bedroom with ensuite.jpg') }}"
                         class="rounded-3" style="width:80px;height:60px;object-fit:cover" alt="Villa">
                    <div>
                        <div class="fw-600" style="font-family:'Cormorant Garamond',serif;font-size:1rem">
                            Villa Superior — Swarna Mandapa
                        </div>
                        <div class="text-muted-sm"><i class="bi bi-geo-alt me-1 text-gold"></i>Jl. Nuansa Angkasa III No 7, Ubud, Bali</div>
                        <div class="d-flex gap-2 mt-1">
                            <span class="facility-tag" style="font-size:.7rem">Private Pool</span>
                            <span class="facility-tag" style="font-size:.7rem">4 Bedrooms</span>
                            <span class="facility-tag" style="font-size:.7rem">456 m²</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-label-sm">Check-in</div>
                        <div class="fw-600">{{ isset($booking['check_in']) ? date('D, d M Y', strtotime($booking['check_in'])) : '—' }}</div>
                        <div class="text-muted-sm">From 15:00</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Check-out</div>
                        <div class="fw-600">{{ isset($booking['check_out']) ? date('D, d M Y', strtotime($booking['check_out'])) : '—' }}</div>
                        <div class="text-muted-sm">Before 11:00</div>
                    </div>
                    @if(isset($booking['check_in'], $booking['check_out']))
                    <div class="col-6">
                        <div class="form-label-sm">Duration</div>
                        <div class="fw-600">
                            {{ (new DateTime($booking['check_in']))->diff(new DateTime($booking['check_out']))->days }} Nights
                        </div>
                    </div>
                    @endif
                    <div class="col-6">
                        <div class="form-label-sm">Guests</div>
                        <div class="fw-600">{{ $booking['guests'] ?? '—' }} Guest{{ ($booking['guests'] ?? 1) > 1 ? 's' : '' }}</div>
                    </div>
                    @if(!empty($booking['arrival_time']))
                    <div class="col-12">
                        <div class="form-label-sm">Estimated Arrival</div>
                        <div class="fw-600">{{ $booking['arrival_time'] }}</div>
                    </div>
                    @endif
                    @if(!empty($booking['special_requests']))
                    <div class="col-12">
                        <div class="form-label-sm">Special Requests</div>
                        <div class="fst-italic" style="font-size:.88rem">{{ $booking['special_requests'] }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Guest Details --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-person-check me-2 text-gold"></i>Guest Details</div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-label-sm">Full Name</div>
                        <div class="fw-600">{{ ($booking['first_name'] ?? '') . ' ' . ($booking['last_name'] ?? '') ?: '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Email</div>
                        <div class="fw-600" style="word-break:break-all">{{ $booking['email'] ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Phone</div>
                        <div class="fw-600">+62 {{ $booking['phone'] ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Country</div>
                        <div class="fw-600">{{ $booking['country'] ?? '—' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="form-label-sm">Booking For</div>
                        <div class="fw-600">{{ ($booking['booking_for'] ?? 'self') === 'self' ? 'I am the main guest' : 'Booking for someone else' }}</div>
                    </div>
                </div>
            </div>

    {{-- Payment Method (Removed since selection happens on DOKU's page) --}}

            {{-- Actions --}}
            <div class="d-flex gap-3 fade-up">
                <a href="{{ route('booking.form') }}" class="btn btn-gold-outline w-50">
                    <i class="bi bi-arrow-left me-2"></i>Back to Edit
                </a>
                <form action="{{ route('booking.confirmation.store') }}" method="POST" class="w-50">
                    @csrf
                    <button type="submit" class="btn btn-gold btn-gold-lg w-100">
                        <i class="bi bi-shield-lock me-2"></i>Confirm Booking
                    </button>
                </form>
            </div>
            <p class="text-center text-muted-sm mt-3">
                <i class="bi bi-lock-fill me-1 text-gold"></i>
                By confirming, you agree to our cancellation policy and terms of service.
            </p>

        </div>

        {{-- Price Sidebar --}}
        <div class="col-lg-4">
            <div class="price-summary-card fade-up" style="position:sticky;top:1.5rem">
                <div class="price-summary-header">
                    <h6 class="mb-0">Price Breakdown</h6>
                    <small>All amounts in IDR</small>
                </div>
                <div class="price-summary-body">
                    @php
                        $nights = 0;
                        if (!empty($booking['check_in']) && !empty($booking['check_out'])) {
                            $nights = (new DateTime($booking['check_in']))->diff(new DateTime($booking['check_out']))->days;
                        }
                        $base  = 4500000 * $nights;
                        $tax   = round($base * 0.11);
                        $fee   = round($base * 0.10);
                        $total = $base + $tax + $fee;
                    @endphp
                    <div class="price-row">
                        <span>Rp 4.500.000 × {{ $nights }} nights</span>
                        <span>Rp {{ number_format($base, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-row"><span>Tax (11%)</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span></div>
                    <div class="price-row"><span>Service Fee (10%)</span><span>Rp {{ number_format($fee, 0, ',', '.') }}</span></div>
                    <div class="price-row total">
                        <span>Total Due</span>
                        <span class="price-total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div class="ornament-divider mt-3"><i class="bi bi-diamond-fill" style="font-size:.4rem"></i></div>

                    <div class="p-3 rounded-3 text-center" style="background:var(--cream);border:1px dashed var(--gold)">
                        <div class="form-label-sm mb-1">Booking Code (Preview)</div>
                        <div style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:600;letter-spacing:.08em;color:var(--gold)">
                            SWM-{{ date('Y') }}-######
                        </div>
                        <div class="text-muted-sm mt-1" style="font-size:.72rem">Generated after confirmation</div>
                    </div>

                    <div class="mt-3 p-3 rounded-3" style="background:#FFF8ED;border:1px solid #F0D9A0;font-size:.78rem">
                        <div class="fw-600" style="color:#8B6914"><i class="bi bi-exclamation-circle me-1"></i>Important</div>
                        <div class="text-muted-sm mt-1">Non-refundable. Full payment required to confirm your stay.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection