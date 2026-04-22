{{-- resources/views/booking/form.blade.php --}}
@extends('layouts.app')
@section('title', 'Book Your Stay — Swarna Mandapa')

@push('styles')
<style>
    .fade-up { animation: fadeUp .5s ease both; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
    .fade-up:nth-child(1){animation-delay:.05s} .fade-up:nth-child(2){animation-delay:.12s}
    .fade-up:nth-child(3){animation-delay:.19s} .fade-up:nth-child(4){animation-delay:.26s}

    .panel{transition:box-shadow .25s}
    .panel:hover{box-shadow:var(--shadow-md)}

    .facility-tag{display:inline-flex;align-items:center;gap:.35rem;background:var(--cream);
        border:1px solid var(--border);border-radius:50px;padding:.3rem .85rem;font-size:.78rem;
        color:var(--text-mid);transition:all .2s;cursor:default}
    .facility-tag:hover{background:var(--gold-pale);border-color:var(--gold);color:var(--gold)}

    .guest-counter{display:flex;align-items:center;gap:.75rem;border:1px solid var(--border);
        border-radius:var(--radius-sm);background:var(--cream);padding:.45rem .9rem}
    .guest-counter button{width:28px;height:28px;border-radius:50%;border:1.5px solid var(--gold);
        background:transparent;color:var(--gold);font-size:1rem;line-height:1;cursor:pointer;
        display:grid;place-items:center;transition:all .2s}
    .guest-counter button:hover{background:var(--gold);color:#fff}
    .guest-counter .gc-num{min-width:28px;text-align:center;font-weight:600;font-size:.95rem}

    .pay-tab{flex:1;padding:.65rem .5rem;border:1.5px solid var(--border);border-radius:var(--radius-md);
        cursor:pointer;background:var(--cream);text-align:center;transition:all .2s;font-size:.78rem;
        color:var(--text-mid);display:flex;flex-direction:column;align-items:center;gap:.25rem}
    .pay-tab i{font-size:1.25rem}
    .pay-tab.active{background:var(--white);border-color:var(--gold);color:var(--gold);
        box-shadow:0 2px 12px rgba(184,146,74,.18)}

    .radio-option{display:flex;align-items:center;gap:.75rem;border:1.5px solid var(--border);
        border-radius:var(--radius-md);padding:.75rem 1rem;cursor:pointer;transition:all .2s;background:var(--cream)}
    .radio-option:has(input:checked){border-color:var(--gold);background:var(--gold-pale)}
    .radio-option input{accent-color:var(--gold);width:16px;height:16px;flex-shrink:0}

    .step-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:2.5rem}
    .step-item{display:flex;flex-direction:column;align-items:center;gap:.3rem}
    .step-line{width:60px;height:2px;background:var(--border);margin:0 .25rem;margin-bottom:1.2rem}
    .step-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);background:var(--white);
        display:grid;place-items:center;font-size:.72rem;font-weight:600;color:var(--text-muted);transition:all .3s}
    .step-item.active .step-dot{border-color:var(--gold);background:var(--gold);color:#fff}
    .step-item.done .step-dot{border-color:var(--success);background:var(--success);color:#fff}
    .step-lbl{font-size:.65rem;letter-spacing:.07em;color:var(--text-muted);text-transform:uppercase}
    .step-item.active .step-lbl{color:var(--gold);font-weight:600}

    @keyframes pulseGold{0%{box-shadow:0 0 0 0 rgba(184,146,74,.4)}70%{box-shadow:0 0 0 10px rgba(184,146,74,0)}100%{box-shadow:0 0 0 0 rgba(184,146,74,0)}}
    .price-summary-card.updated{animation:pulseGold .6s ease}

    .form-control-swarna.is-invalid{border-color:var(--danger) !important}
    .invalid-hint{font-size:.73rem;color:var(--danger);margin-top:.25rem;display:none}
    .is-invalid~.invalid-hint,.form-control-swarna.is-invalid+.invalid-hint{display:block}

    @keyframes spin{to{transform:rotate(360deg)}}
    .spin{animation:spin .7s linear infinite;display:inline-block;width:16px;height:16px;
        border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%}

    input[type=date]::-webkit-calendar-picker-indicator{filter:opacity(.4);cursor:pointer}
</style>
@endpush

@section('content')

<div class="page-header fade-up">
    <span class="section-label">Reservations</span>
    <h1>Book Your <em>Escape</em></h1>
    <p>Complete your booking details below and proceed to payment.</p>
</div>

<div class="container pb-5">

    {{-- Progress Steps --}}
    <div class="step-wrap">
        <div class="step-item active">
            <div class="step-dot">1</div>
            <div class="step-lbl">Details</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
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

    <form action="{{ route('booking.store') }}" method="POST" id="booking-form" novalidate>
    @csrf

    <div class="row g-4 align-items-start">

        {{-- ════ LEFT ════ --}}
        <div class="col-lg-7">

            {{-- 1. Stay Details --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-house-heart me-2 text-gold"></i>Your Stay Details</div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="form-label-sm">Villa Name</div>
                        <div class="fw-600">Villa Superior</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Accommodation</div>
                        <div class="fw-600">Entire Villa (1 unit)</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Villa Size</div>
                        <div class="fw-600">456 m²</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Maximum Guests</div>
                        <div class="fw-600">Up to 10 guests</div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-label-sm mb-2">Bedrooms</div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="facility-tag"><i class="bi bi-moon-stars text-gold"></i>Master Guest Suite</span>
                        <span class="facility-tag"><i class="bi bi-moon-stars text-gold"></i>Super-King Bedroom</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-label-sm mb-1">Bathrooms</div>
                    <div class="fw-600">5 private bathrooms</div>
                </div>
                <div>
                    <div class="form-label-sm mb-2">Exclusive Facilities</div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="facility-tag"><i class="bi bi-water text-gold"></i>Private Pool</span>
                        <span class="facility-tag"><i class="bi bi-cup-hot text-gold"></i>Private Kitchen</span>
                        <span class="facility-tag"><i class="bi bi-building text-gold"></i>Balcony & Terrace</span>
                        <span class="facility-tag"><i class="bi bi-droplet text-gold"></i>Bathtub & Jacuzzi</span>
                        <span class="facility-tag"><i class="bi bi-thermometer-sun text-gold"></i>Climate AC</span>
                        <span class="facility-tag"><i class="bi bi-wifi text-gold"></i>High-Speed Wi-Fi</span>
                        <span class="facility-tag"><i class="bi bi-person-badge text-gold"></i>24/7 Butler Service</span>
                        <span class="facility-tag"><i class="bi bi-flower1 text-gold"></i>In-Villa Spa</span>
                        <span class="facility-tag"><i class="bi bi-tv text-gold"></i>Smart TV Netflix</span>
                        <span class="facility-tag"><i class="bi bi-basket text-gold"></i>Floating Breakfast</span>
                        <span class="facility-tag"><i class="bi bi-fire text-gold"></i>BBQ Grill Setup</span>
                        <span class="facility-tag"><i class="bi bi-stars text-gold"></i>Daily Housekeeping</span>
                    </div>
                </div>
            </div>

            {{-- 2. Booking Dates --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-calendar3 me-2 text-gold"></i>Your Booking Details</div>
                <p class="text-muted-sm mb-3">Select your check-in, check-out dates and number of guests.</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label-sm">Check-in <span class="required-star">*</span></label>
                        <input type="date" name="check_in" id="check_in"
                               class="form-control form-control-swarna"
                               min="{{ date('Y-m-d') }}" required>
                        <div class="invalid-hint">Please select check-in date</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-sm">Check-out <span class="required-star">*</span></label>
                        <input type="date" name="check_out" id="check_out"
                               class="form-control form-control-swarna" required>
                        <div class="invalid-hint">Please select check-out date</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-sm">Guests <span class="required-star">*</span></label>
                        <input type="hidden" name="guests" id="guests-val" value="2">
                        <div class="guest-counter">
                            <button type="button" id="g-minus"><i class="bi bi-dash"></i></button>
                            <span class="gc-num" id="g-num">2</span>
                            <button type="button" id="g-plus"><i class="bi bi-plus"></i></button>
                            <span class="text-muted-sm" id="g-label">Guests</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Guest Info --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-person me-2 text-gold"></i>Guest Information</div>
                <p class="text-muted-sm mb-3">Fields marked with <span class="required-star">*</span> are required.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-sm">First Name <span class="required-star">*</span></label>
                        <input type="text" name="first_name" class="form-control form-control-swarna" placeholder="First name" required>
                        <div class="invalid-hint">First name is required</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sm">Last Name <span class="required-star">*</span></label>
                        <input type="text" name="last_name" class="form-control form-control-swarna" placeholder="Last name" required>
                        <div class="invalid-hint">Last name is required</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Email Address <span class="required-star">*</span></label>
                        <input type="email" name="email" class="form-control form-control-swarna" placeholder="your@email.com" required>
                        <small class="text-muted-sm"><i class="bi bi-info-circle me-1 text-gold"></i>Booking confirmation will be sent here</small>
                        <div class="invalid-hint">Please enter a valid email</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sm">Phone <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--cream);border:1px solid var(--border);border-right:0;font-size:.85rem;">🇮🇩 +62</span>
                            <input type="tel" name="phone" class="form-control form-control-swarna" placeholder="812 3456 7890" style="border-left:0;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sm">Country <span class="required-star">*</span></label>
                        <select name="country" class="form-select form-control-swarna">
                            <option value="ID" selected>🇮🇩 Indonesia</option>
                            <option value="AU">🇦🇺 Australia</option>
                            <option value="SG">🇸🇬 Singapore</option>
                            <option value="US">🇺🇸 United States</option>
                            <option value="GB">🇬🇧 United Kingdom</option>
                            <option value="JP">🇯🇵 Japan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="form-label-sm mb-2">Who are you booking for?</div>
                        <div class="d-flex gap-2">
                            <label class="radio-option flex-grow-1">
                                <input type="radio" name="booking_for" value="self" checked>
                                <div>
                                    <div class="fw-500" style="font-size:.88rem">I am the main guest</div>
                                    <div class="text-muted-sm" style="font-size:.74rem">Booking details match my info</div>
                                </div>
                            </label>
                            <label class="radio-option flex-grow-1">
                                <input type="radio" name="booking_for" value="other">
                                <div>
                                    <div class="fw-500" style="font-size:.88rem">Booking for someone else</div>
                                    <div class="text-muted-sm" style="font-size:.74rem">I'll enter their details</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Special Requests</label>
                        <textarea name="special_requests" class="form-control form-control-swarna" rows="3"
                            placeholder="e.g. early check-in, dietary requirements, honeymoon setup, airport transfer..."></textarea>
                        <small class="text-muted-sm">The property will do its best to accommodate your requests.</small>
                    </div>
                </div>
            </div>

            {{-- 4. Arrival Time --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-clock me-2 text-gold"></i>Your Arrival Time</div>
                <div class="d-flex align-items-center gap-2 p-3 rounded-3 mb-3"
                     style="background:var(--cream-dark);border:1px solid var(--border);">
                    <i class="bi bi-check-circle-fill text-gold"></i>
                    <span style="font-size:.85rem">You can check in between <strong>15:00</strong> and <strong>23:30</strong></span>
                </div>
                <label class="form-label-sm">Estimated Arrival Time</label>
                <select name="arrival_time" class="form-select form-control-swarna" style="max-width:220px">
                    <option value="">Please select</option>
                    @foreach(['15:00 – 16:00','16:00 – 17:00','17:00 – 18:00','18:00 – 19:00','19:00 – 20:00','20:00 – 21:00','21:00 – 22:00','22:00 – 23:00','23:00 – 23:30'] as $t)
                        <option>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 5. Payment --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-shield-lock me-2 text-gold"></i>Secure Payment</div>
                <div class="d-flex align-items-center flex-wrap gap-2 p-3 rounded-3 mb-4"
                     style="background:var(--cream-dark);border:1px solid var(--border);font-size:.82rem">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-people-fill text-gold"></i>
                        <span>You'll get the <strong>entire villa</strong> to yourself!</span>
                    </div>
                    <span class="d-none d-sm-inline opacity-50">·</span>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-exclamation text-danger"></i>
                        <span style="color:var(--danger);font-weight:500">Non-refundable</span>
                    </div>
                    <span class="d-none d-sm-inline opacity-50">·</span>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-slash-circle"></i>
                        <span>No smoking</span>
                    </div>
                </div>

                <input type="hidden" name="payment_method" id="pay-method-val" value="card">
                <div class="d-flex gap-2 mb-4">
                    <div class="pay-tab active" onclick="setPayTab(this,'card')">
                        <i class="bi bi-credit-card-2-front"></i><span>Credit / Debit Card</span>
                    </div>
                    <div class="pay-tab" onclick="setPayTab(this,'va')">
                        <i class="bi bi-bank"></i><span>Virtual Account</span>
                    </div>
                    <div class="pay-tab" onclick="setPayTab(this,'ewallet')">
                        <i class="bi bi-wallet2"></i><span>E-Wallet</span>
                    </div>
                </div>

                {{-- Card --}}
                <div id="pf-card">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-sm">Card Number</label>
                            <input type="text" name="card_number" id="card-num"
                                   class="form-control form-control-swarna"
                                   placeholder="1234  5678  9012  3456" maxlength="22">
                        </div>
                        <div class="col-6">
                            <label class="form-label-sm">Expiry</label>
                            <input type="text" name="card_expiry" id="card-exp"
                                   class="form-control form-control-swarna" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="col-6">
                            <label class="form-label-sm">CVV</label>
                            <div class="position-relative">
                                <input type="password" name="card_cvv" id="card-cvv"
                                       class="form-control form-control-swarna" placeholder="•••" maxlength="4">
                                <button type="button" onclick="toggleCvv()"
                                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted)">
                                    <i class="bi bi-eye" id="cvv-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-sm">Cardholder Name</label>
                            <input type="text" name="card_name" class="form-control form-control-swarna" placeholder="Name as on card">
                        </div>
                        <div class="col-12">
                            <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.85rem">
                                <input type="checkbox" name="save_card" style="accent-color:var(--gold);width:16px;height:16px">
                                Save card for future bookings
                            </label>
                        </div>
                    </div>
                </div>

                {{-- VA --}}
                <div id="pf-va" style="display:none">
                    <label class="form-label-sm">Select Bank</label>
                    <select name="va_bank" class="form-select form-control-swarna mb-3">
                        <option>BCA</option><option>BNI</option><option>BRI</option>
                        <option>Mandiri</option><option>CIMB Niaga</option>
                    </select>
                    <div class="p-3 rounded-3" style="background:var(--cream);border:1px solid var(--border);font-size:.82rem">
                        <i class="bi bi-info-circle text-gold me-1"></i>
                        A virtual account number will be generated after submission. Payment must be completed within 24 hours.
                    </div>
                </div>

                {{-- Ewallet --}}
                <div id="pf-ewallet" style="display:none">
                    <div class="form-label-sm mb-2">Select Wallet</div>
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        @foreach(['GoPay','OVO','Dana','ShopeePay'] as $w)
                        <label class="radio-option" style="flex:1;min-width:110px">
                            <input type="radio" name="ewallet" value="{{ strtolower($w) }}" {{ $loop->first?'checked':'' }}>
                            <span style="font-size:.88rem;font-weight:500">{{ $w }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="p-3 rounded-3" style="background:var(--cream);border:1px solid var(--border);font-size:.82rem">
                        <i class="bi bi-info-circle text-gold me-1"></i>
                        You'll be redirected to your e-wallet app to complete the payment.
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" id="btn-submit"
                    class="btn btn-gold btn-gold-lg w-100 d-flex align-items-center justify-content-center gap-2">
                <span id="btn-spinner" class="spin" style="display:none"></span>
                <i class="bi bi-shield-lock" id="btn-icon"></i>
                <span id="btn-txt">Proceed to Payment</span>
            </button>
            <p class="text-center text-muted-sm mt-2">
                <i class="bi bi-lock-fill me-1 text-gold"></i>Secured with 256-bit SSL encryption
            </p>

        </div>

        {{-- ════ RIGHT ════ --}}
        <div class="col-lg-5">

            {{-- Villa Image Card --}}
            <div class="panel mb-3 p-0 overflow-hidden fade-up">
                <div class="position-relative">
                    <img src="{{ asset('images/Master Bedroom with ensuite.jpg') }}"
                         alt="Villa" class="img-fluid w-100" style="height:210px;object-fit:cover">
                    <div class="position-absolute bottom-0 start-0 end-0 p-3"
                         style="background:linear-gradient(to top,rgba(0,0,0,.55),transparent)">
                        <div class="text-white fw-600" style="font-family:'Cormorant Garamond',serif;font-size:1.05rem">
                            Swarna Mandapa — 4 Bedroom Pool Villa
                        </div>
                        <div style="font-size:.75rem;color:rgba(255,255,255,.85)">
                            <i class="bi bi-geo-alt me-1"></i>Jl. Nuansa Angkasa III No 7, Ubud, Bali
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-1">
                            @for($i=0;$i<5;$i++)<i class="bi bi-star-fill text-gold" style="font-size:.72rem"></i>@endfor
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="fw-700 text-white px-2 py-1 rounded-2" style="background:var(--text-dark);font-size:.75rem">10</span>
                            <span class="text-muted-sm">Exceptional · 1 review</span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach(['Private Pool', 'Private Kitchen', 'Balcony & Terrace', 'Bathtub', 'Air Conditioning', 'Free Wi-Fi'] as $f)
                        <span style="background:var(--cream);border:1px solid var(--border);border-radius:50px;padding:.2rem .75rem;font-size:.72rem;color:var(--text-mid)">{{ $f }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Price Summary --}}
            <div class="price-summary-card fade-up" id="price-card">
                <div class="price-summary-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Price Summary</h6>
                            <small>Villa Superior · Up to 10 guests</small>
                        </div>
                        <i class="bi bi-receipt fs-4 text-white opacity-75"></i>
                    </div>
                </div>
                <div class="price-summary-body">

                    {{-- Date miniview --}}
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-3"
                         style="background:var(--cream);border:1px solid var(--border)">
                        <div class="text-center">
                            <div class="form-label-sm">Check-in</div>
                            <div class="fw-600" id="s-ci" style="font-size:.83rem">— select</div>
                            <div class="text-muted-sm" style="font-size:.7rem">15:00</div>
                        </div>
                        <i class="bi bi-arrow-right text-gold"></i>
                        <div class="text-center">
                            <div class="form-label-sm">Check-out</div>
                            <div class="fw-600" id="s-co" style="font-size:.83rem">— select</div>
                            <div class="text-muted-sm" style="font-size:.7rem">11:00</div>
                        </div>
                    </div>

                    <div id="s-breakdown" style="display:none">
                        <div class="price-row">
                            <span>Rp 4.500.000 × <span id="s-nights">0</span> nights</span>
                            <span id="s-base">—</span>
                        </div>
                        <div class="price-row"><span>Tax (11%)</span><span id="s-tax">—</span></div>
                        <div class="price-row"><span>Service Fee (10%)</span><span id="s-fee">—</span></div>
                    </div>

                    {{-- Promo --}}
                    <div class="my-3">
                        <label class="form-label-sm">Promo Code</label>
                        <div class="input-group">
                            <input type="text" id="promo-inp" class="form-control form-control-swarna"
                                   placeholder="e.g. WELCOME10">
                            <button type="button" class="btn btn-gold px-3" onclick="applyPromo()"
                                style="border-radius:0 var(--radius-sm) var(--radius-sm) 0">Apply</button>
                        </div>
                        <div id="promo-msg" style="font-size:.74rem;margin-top:.3rem;display:none"></div>
                    </div>

                    <div id="s-disc-row" class="price-row" style="display:none;color:var(--success)">
                        <span><i class="bi bi-tag me-1"></i>Promo Discount</span>
                        <span id="s-disc">—</span>
                    </div>

                    <div class="price-row total">
                        <span>Total</span>
                        <span class="price-total-amount" id="s-total">Rp —</span>
                    </div>

                    <div class="ornament-divider mt-3"><i class="bi bi-diamond-fill" style="font-size:.4rem"></i></div>

                    <div class="d-flex flex-column gap-2" style="font-size:.8rem">
                        <div><i class="bi bi-shield-check me-2 text-gold"></i>Secure & encrypted payments</div>
                        <div><i class="bi bi-arrow-counterclockwise me-2 text-gold"></i>Free cancellation up to 7 days</div>
                        <div><i class="bi bi-lightning-charge me-2 text-gold"></i>Instant booking confirmation</div>
                    </div>

                    <div class="mt-3 p-3 rounded-3" style="background:var(--cream);border:1px solid var(--border);font-size:.78rem">
                        <div class="fw-600 text-gold mb-1">Cancellation Policy</div>
                        <div class="text-muted-sm">Non-refundable after booking is confirmed.</div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
const RATE=4500000, TAX=.11, FEE=.10;
let promoDiscount=0;
const PROMOS={'WELCOME10':.10,'SWARNA20':.20,'BALI15':.15};
const idr=n=>'Rp '+Math.round(n).toLocaleString('id-ID').replace(/,/g,'.');
const fmtD=s=>{ if(!s) return '— select'; const d=new Date(s+'T00:00:00'); return d.toLocaleDateString('en-GB',{day:'numeric',month:'short',year:'numeric'}); };

// Guest counter
let gc=2;
document.getElementById('g-minus').onclick=()=>{ if(gc>1){gc--;upd()} };
document.getElementById('g-plus').onclick=()=>{ if(gc<10){gc++;upd()} };
function upd(){
    document.getElementById('g-num').textContent=gc;
    document.getElementById('guests-val').value=gc;
    document.getElementById('g-label').textContent=gc>1?'Guests':'Guest';
}

// Price calc
function calc(){
    const ci=document.getElementById('check_in').value;
    const co=document.getElementById('check_out').value;
    document.getElementById('s-ci').textContent=fmtD(ci);
    document.getElementById('s-co').textContent=fmtD(co);
    if(!ci||!co) return;
    const nights=Math.round((new Date(co)-new Date(ci))/86400000);
    if(nights<=0) return;
    const base=RATE*nights, tax=base*TAX, fee=base*FEE;
    const disc=base*promoDiscount, total=base+tax+fee-disc;
    document.getElementById('s-nights').textContent=nights;
    document.getElementById('s-base').textContent=idr(base);
    document.getElementById('s-tax').textContent=idr(tax);
    document.getElementById('s-fee').textContent=idr(fee);
    document.getElementById('s-total').textContent=idr(total);
    document.getElementById('s-breakdown').style.display='block';
    if(disc>0){ document.getElementById('s-disc').textContent='— '+idr(disc); document.getElementById('s-disc-row').style.display='flex'; }
    const c=document.getElementById('price-card');
    c.classList.remove('updated'); void c.offsetWidth; c.classList.add('updated');
}
document.getElementById('check_in').addEventListener('change',function(){
    const d=new Date(this.value); d.setDate(d.getDate()+1);
    document.getElementById('check_out').min=d.toISOString().split('T')[0];
    calc();
});
document.getElementById('check_out').addEventListener('change',calc);

// Promo
function applyPromo(){
    const code=document.getElementById('promo-inp').value.trim().toUpperCase();
    const el=document.getElementById('promo-msg'); el.style.display='block';
    if(PROMOS[code]){ promoDiscount=PROMOS[code]; el.style.color='var(--success)'; el.innerHTML='<i class="bi bi-check-circle me-1"></i>Promo applied! '+(promoDiscount*100)+'% off.'; calc(); }
    else { el.style.color='var(--danger)'; el.innerHTML='<i class="bi bi-x-circle me-1"></i>Invalid promo code.'; }
}

// Pay tabs
function setPayTab(el,m){
    document.querySelectorAll('.pay-tab').forEach(t=>t.classList.remove('active'));
    el.classList.add('active'); document.getElementById('pay-method-val').value=m;
    ['card','va','ewallet'].forEach(x=>document.getElementById('pf-'+x).style.display=x===m?'block':'none');
}

// CVV toggle
function toggleCvv(){
    const i=document.getElementById('card-cvv'),ic=document.getElementById('cvv-icon');
    i.type=i.type==='password'?'text':'password';
    ic.className='bi bi-eye'+(i.type==='text'?'-slash':'');
}

// Card format
document.getElementById('card-num').addEventListener('input',function(){ this.value=this.value.replace(/\D/g,'').replace(/(.{4})/g,'$1  ').trim().slice(0,22); });
document.getElementById('card-exp').addEventListener('input',function(){ this.value=this.value.replace(/\D/g,'').replace(/^(\d{2})(\d)/,'$1/$2').slice(0,5); });

// Form submit validation + loading
document.getElementById('booking-form').addEventListener('submit',function(e){
    let ok=true;
    const req=[['check_in','check_in'],['check_out','check_out'],['first_name',null],['email',null]];
    req.forEach(([name,id])=>{
        const el=id?document.getElementById(id):document.querySelector('[name='+name+']');
        if(el&&!el.value.trim()){ el.classList.add('is-invalid'); ok=false; }
    });
    if(!ok){ e.preventDefault(); window.scrollTo({top:0,behavior:'smooth'}); return; }
    document.getElementById('btn-spinner').style.display='inline-block';
    document.getElementById('btn-icon').style.display='none';
    document.getElementById('btn-txt').textContent='Processing...';
    document.getElementById('btn-submit').disabled=true;
});
document.querySelectorAll('.form-control-swarna').forEach(el=>{ el.addEventListener('input',()=>el.classList.remove('is-invalid')); });
</script>
@endpush