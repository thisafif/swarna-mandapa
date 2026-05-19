{{-- resources/views/booking/invoice.blade.php --}}
@extends('layouts.app')
@section('title', 'Invoice & Payment — Swarna Mandapa')

@push('styles')
<style>
    .fade-up{animation:fadeUp .5s ease both}
    @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
    .step-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:2.5rem}
    .step-item{display:flex;flex-direction:column;align-items:center;gap:.3rem}
    .step-line{width:60px;height:2px;background:var(--border);margin:0 .25rem;margin-bottom:1.2rem}
    .step-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:grid;place-items:center;font-size:.72rem;font-weight:600;color:var(--text-muted)}
    .step-item.active .step-dot{border-color:var(--gold);background:var(--gold);color:#fff}
    .step-item.done .step-dot{border-color:var(--success);background:var(--success);color:#fff}
    .step-lbl{font-size:.65rem;letter-spacing:.07em;color:var(--text-muted);text-transform:uppercase}
    .step-item.active .step-lbl{color:var(--gold);font-weight:600}
    .step-item.done .step-lbl{color:var(--success)}

    .modal-gateway .modal-content{border:none;border-radius:20px;overflow:hidden;background:var(--cream)}
    .gateway-header{background:linear-gradient(135deg,#C9A96E,#8B6914);padding:2rem;text-align:center}
    @keyframes spin{to{transform:rotate(360deg)}}
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

    {{-- Booking Created Banner --}}
    <div class="rounded-4 p-4 mb-4 text-white fade-up"
         style="background:linear-gradient(135deg,#C9A96E 0%,#8B6914 100%)">
        <div class="form-label-sm text-white mb-1" style="opacity:.8">Booking Created</div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:600;letter-spacing:.08em">
            SWM-{{ date('Y') }}-000101
        </div>
        <div class="mt-2 d-flex align-items-center gap-2">
            <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;border-radius:50px;font-size:.72rem;padding:.3em .9em">
                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle"></i>REQUESTED
            </span>
            <span style="font-size:.82rem;opacity:.85">Your booking is being processed</span>
        </div>
    </div>

    {{-- Notice --}}
    <div class="d-flex align-items-center gap-2 p-3 rounded-3 mb-3 fade-up"
         style="background:var(--cream-dark);border:1px solid var(--border);font-size:.82rem">
        <i class="bi bi-clock-history text-gold flex-shrink-0"></i>
        Save your booking code to track your reservation status at any time. No account needed.
    </div>

    {{-- Booking Details --}}
    <div class="panel fade-up">
        <div class="panel-title"><i class="bi bi-info-circle me-2 text-gold"></i>Booking Details</div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-label-sm">Guest Name</div>
                <div class="fw-600">{{ ($booking['first_name'] ?? 'Guest') . ' ' . ($booking['last_name'] ?? '') }}</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Email</div>
                <div class="fw-600" style="word-break:break-all">{{ $booking['email'] ?? '—' }}</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Check-in</div>
                <div class="fw-600">{{ isset($booking['check_in']) ? date('D, d M Y', strtotime($booking['check_in'])) : '—' }}</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Check-out</div>
                <div class="fw-600">{{ isset($booking['check_out']) ? date('D, d M Y', strtotime($booking['check_out'])) : '—' }}</div>
            </div>
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
            <div class="col-md-6">
                <div class="form-label-sm">Duration</div>
                <div class="fw-600">{{ $nights }} Nights</div>
            </div>
            <div class="col-md-6">
                <div class="form-label-sm">Guests</div>
                <div class="fw-600">{{ $booking['guests'] ?? '—' }} Guests</div>
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
    <div class="panel fade-up">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="form-label-sm">Invoice</div>
                <div class="fw-700" style="font-size:1rem">INV-SWM-{{ rand(10000000, 99999999) }}</div>
            </div>
            <span class="badge badge-pending">Awaiting Payment</span>
        </div>
        <div class="row g-2 mb-3" style="font-size:.85rem">
            <div class="col-6"><div class="form-label-sm">Booking Code</div><div class="fw-600">SWM-{{ date('Y') }}-000101</div></div>
            <div class="col-6"><div class="form-label-sm">Invoice Date</div><div class="fw-600">{{ date('d M Y') }}</div></div>
            <div class="col-6"><div class="form-label-sm">Billed To</div><div class="fw-600">{{ ($booking['first_name'] ?? 'Guest') . ' ' . ($booking['last_name'] ?? '') }}</div></div>
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
                            — {{ date('d M Y', strtotime($booking['check_in'])) }} → {{ date('d M Y', strtotime($booking['check_out'])) }} ({{ $nights }} nights)
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

        <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top:1px solid var(--border);font-size:.8rem">
            <span class="text-muted-sm"><i class="bi bi-exclamation-triangle me-1 text-gold"></i>Non-refundable. Complete payment to confirm your booking.</span>
            <span class="badge badge-pending">Awaiting Payment</span>
        </div>

        {{-- Payment deadline --}}
        <div class="mt-3 p-3 rounded-3 d-flex align-items-center gap-2" style="background:#FFF8ED;border:1px solid #F0D9A0;font-size:.82rem">
            <i class="bi bi-clock-history text-warning fs-5 flex-shrink-0"></i>
            <div>
                <div class="fw-600" style="color:#8B6914">Payment Deadline</div>
                <div class="text-muted-sm">Complete payment by <strong>{{ date('d M Y', strtotime('+24 hours')) }}, 23:59 WIB</strong> to secure your reservation.</div>
            </div>
        </div>
    </div>

    {{-- Ready to Pay --}}
    <div class="panel fade-up">
        <div class="panel-title"><i class="bi bi-lightning-charge me-2 text-gold"></i>Ready to Pay?</div>
        <p class="text-muted-sm mb-4">Clicking Pay Now will redirect you to our secure payment gateway. Your booking status will update automatically.</p>

        <button class="btn btn-gold btn-gold-lg w-100 mb-3" id="btn-pay">
            <i class="bi bi-credit-card me-2"></i>Pay Now — Rp {{ number_format($total, 0, ',', '.') }}
        </button>
        <a href="#" class="btn btn-gold-outline w-100 mb-2" onclick="window.print()">
            <i class="bi bi-download me-2"></i>Download Invoice (PDF)
        </a>
        <p class="text-center text-muted-sm mt-2">
            <i class="bi bi-lock-fill me-1 text-gold"></i>256-bit SSL · Powered by Midtrans
        </p>
    </div>

</div>

{{-- Payment Gateway Modal --}}
<div class="modal fade modal-gateway" id="payModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content">
            <div class="gateway-header">
                <div class="text-white fw-600" style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;letter-spacing:.05em">
                    Secure Payment Gateway
                </div>
                <div style="color:rgba(255,255,255,.8);font-size:.78rem;margin-top:.25rem">
                    You are being redirected to our payment processor
                </div>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="border rounded-3 py-3 px-4 mb-4" style="background:var(--white)">
                    <div style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:500">BLA BLA Pay</div>
                    <div class="text-muted-sm">International Payment Gateway · SSL Secured</div>
                </div>
                <div class="mb-4">
                    <div class="form-label-sm">Amount Due</div>
                    <div style="font-size:2rem;font-weight:700;font-family:'Cormorant Garamond',serif;color:var(--text-dark)">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                    <div class="text-muted-sm mt-1">Booking Code: SWM-{{ date('Y') }}-000101</div>
                </div>
                <a href="{{ route('booking.status') }}" class="btn btn-gold btn-gold-lg w-100 mb-2">
                    <i class="bi bi-check-lg me-2"></i>Confirm Payment
                </a>
                <button class="btn btn-gold-outline w-100" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Simulate Failed Payment
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('btn-pay').addEventListener('click',function(){
    new bootstrap.Modal(document.getElementById('payModal')).show();
});
</script>
@endpush