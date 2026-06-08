{{-- resources/views/booking/confirmation.blade.php --}}
@extends('layouts.app')
@section('title', 'Confirm Booking — Swarna Mandapa')

@push('styles')
<style>
    .fade-up{animation:fadeUp .5s ease both}
    @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
    .step-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:2.5rem}
    .step-item{display:flex;flex-direction:column;align-items:center;gap:.3rem}
    .step-line{width:60px;height:2px;background:var(--border);margin:0 .25rem;margin-bottom:1.2rem}
    .step-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:grid;place-items:center;font-size:.72rem;font-weight:600;color:var(--text-muted);transition:all .3s}
    .step-item.active .step-dot{border-color:var(--gold);background:var(--gold);color:#fff}
    .step-item.done .step-dot{border-color:var(--success);background:var(--success);color:#fff}
    .step-lbl{font-size:.65rem;letter-spacing:.07em;color:var(--text-muted);text-transform:uppercase}
    .step-item.active .step-lbl{color:var(--gold);font-weight:600}
    .step-item.done .step-lbl{color:var(--success)}
    .terms-panel{background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:18px;box-shadow:0 14px 36px rgba(0,0,0,.05);overflow:hidden}
    .terms-header{padding:1.5rem 1.75rem;background:linear-gradient(135deg,#FFFDF8 0%,#F7EEDC 100%);border-bottom:1px solid #EBE4D5;display:flex;align-items:center;justify-content:space-between;gap:1rem}
    .terms-header-copy{min-width:0}
    .terms-eyebrow{font-size:.68rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:.35rem}
    .terms-title{font-family:'Cormorant Garamond',serif;font-size:1.65rem;line-height:1.15;color:var(--text-dark);margin:0;max-width:760px}
    .terms-preview{padding:1.1rem 1.25rem;background:#FFFEFC;color:var(--text-muted);font-size:.86rem;border-bottom:1px solid #F0E8DB}
    .terms-open-btn{white-space:nowrap;display:inline-flex;align-items:center;justify-content:center}
    .terms-panel > .terms-scroll{display:none}
    .terms-scroll{max-height:520px;overflow-y:auto;padding:1.25rem;background:#FFFEFC}
    .terms-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.9rem;color:#50483f;font-size:.8rem;line-height:1.55}
    .term-item{background:#fff;border:1px solid #EFE8DA;border-radius:12px;padding:1rem}
    .term-heading{font-size:.82rem;font-weight:800;color:#4f463d;margin-bottom:.55rem;display:flex;gap:.45rem;align-items:flex-start}
    .term-heading span{color:var(--gold);font-weight:800;flex:0 0 auto}
    .term-item p{margin:0 0 .65rem}
    .term-item p:last-child{margin-bottom:0}
    .term-item ul{padding-left:1.1rem;margin:.5rem 0 0}
    .term-item li{margin-bottom:.45rem}
    .term-item.wide{grid-column:1 / -1}
    .terms-section-title{grid-column:1 / -1;font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:var(--text-dark);margin:.25rem 0 .2rem;padding-bottom:.5rem;border-bottom:1px solid #EBE4D5}
    .terms-agreement{padding:1rem 1.25rem;border-top:1px solid #EBE4D5;background:var(--cream);display:flex;gap:.75rem;align-items:flex-start}
    .terms-agreement input{width:18px;height:18px;margin-top:.15rem;accent-color:var(--gold);flex:0 0 auto}
    .terms-agreement label{font-size:.9rem;font-weight:700;color:var(--text-dark);cursor:pointer}
    .terms-agreement small{display:block;color:var(--text-muted);font-size:.76rem;font-weight:400;margin-top:.15rem}
    .confirmation-actions{margin-top:1.5rem}
    .btn-confirm-disabled:disabled{opacity:.5;cursor:not-allowed}
    .terms-modal .modal-content{border:0;border-radius:18px;overflow:hidden}
    .terms-modal .modal-header{background:linear-gradient(135deg,#FFFDF8 0%,#F7EEDC 100%);border-bottom:1px solid #EBE4D5}
    .terms-modal .modal-title{font-family:'Cormorant Garamond',serif;font-size:1.45rem;font-weight:600;color:var(--text-dark)}
    .terms-modal .modal-body{padding:0;overflow:hidden}
    .terms-modal .terms-scroll{max-height:68vh;overflow-y:auto;overscroll-behavior:contain;scrollbar-gutter:stable}
    body.terms-modal-lock{overflow:hidden!important;overscroll-behavior:none}
    .terms-done-button:disabled{opacity:.5;cursor:not-allowed}
    @media (max-width:991px){.booking-confirm-price .price-summary-card{position:static!important;margin-bottom:1.25rem}}
    @media (max-width:767px){.terms-header{padding:1.2rem;flex-direction:column;align-items:flex-start}.terms-title{font-size:1.35rem}.terms-open-btn{width:100%}.terms-scroll{max-height:560px;padding:.9rem}.terms-list{grid-template-columns:1fr;font-size:.83rem}.term-item.wide{grid-column:auto}.terms-agreement{align-items:flex-start}.confirmation-actions{margin-top:1.25rem}}
</style>
@endpush

@section('content')

<div class="page-header fade-up">
    <span class="section-label">Reservations</span>
    <h1>Review & <em>Confirm</em></h1>
    <p>Please review your booking details before proceeding to payment.</p>
</div>

<div class="container pb-5">

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
        <div class="col-lg-7 order-2 order-lg-1">

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

            {{-- Guest Details — tanpa Country --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-person-check me-2 text-gold"></i>Guest Details</div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-label-sm">Full Name</div>
                        <div class="fw-600">{{ trim(($booking['first_name'] ?? '') . ' ' . ($booking['last_name'] ?? '')) ?: '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Email</div>
                        <div class="fw-600" style="word-break:break-all">{{ $booking['email'] ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Phone</div>
                        <div class="fw-600">{{ $booking['phone'] ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="form-label-sm">Booking For</div>
                        <div class="fw-600">{{ ($booking['booking_for'] ?? 'self') === 'self' ? 'I am the main guest' : 'Booking for someone else' }}</div>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="panel fade-up">
                <div class="panel-title"><i class="bi bi-credit-card me-2 text-gold"></i>Payment Method</div>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="background:var(--cream);width:52px;height:40px">
                        <i class="bi bi-shield-check text-gold fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-600">Secure Payment via DOKU</div>
                        <div class="text-muted-sm">You will be redirected to choose your preferred payment method</div>
                    </div>
                </div>
            </div>

            {{-- Terms of Rent --}}
            <div class="terms-panel fade-up">
                <div class="terms-header">
                    <div class="terms-header-copy">
                        <div class="terms-eyebrow">Terms of Rent</div>
                        <h2 class="terms-title">Following are the terms of agreement regarding the rental of Swarna Mandapa.</h2>
                    </div>
                    <button type="button" class="btn btn-gold-outline terms-open-btn" data-bs-toggle="modal" data-bs-target="#termsOfRentModal">
                        <i class="bi bi-file-text me-2"></i>View Terms
                    </button>
                </div>
                <div class="terms-preview">
                    Please review the complete rental agreement before confirming your booking. The full Terms of Rent opens in a modal for easier reading.
                </div>
                <div class="terms-scroll" id="termsOfRentContent">
                    <div class="terms-list">
                        <div class="terms-section-title">Swarna Mandapa Terms of Rental</div>
                        <div class="term-item wide">
                            <p>The following terms apply to all bookings and stays at Swarna Mandapa properties in Indonesia.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>1.</span> Property Condition</div>
                            <p>The property will be provided in a clean, tidy, and ready-to-use condition. Guests must leave the property in a similar condition upon departure.</p>
                            <p>Guests are responsible for washing used utensils, keeping the property tidy, and placing waste in the designated bins before check-out.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>2.</span> Linen and Facilities</div>
                            <p>Bed linen and standard towels are provided unless stated otherwise in the booking confirmation.</p>
                            <p>The property is furnished and equipped for short-term holiday accommodation. Any additional requests may be subject to availability and additional charges.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>3.</span> Guest Occupancy</div>
                            <p>The number of guests staying at the property must not exceed the number stated in the booking confirmation.</p>
                            <p>If the number of occupants exceeds the approved limit, Swarna Mandapa may terminate the stay immediately without refund for the remaining nights.</p>
                        </div>
                        <div class="term-item wide">
                            <div class="term-heading"><span>4.</span> Damage, Breakages, and Additional Cleaning</div>
                            <p>Guests are responsible for any damage, breakage, missing items, additional cleaning, or additional waste removal caused during the rental period, whether accidental or intentional.</p>
                            <p>Any damage or breakage must be reported to Swarna Mandapa before departure.</p>
                            <p>Swarna Mandapa may charge the guest for repair, replacement, additional cleaning, or restoration costs required to return the property to its original condition.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>5.</span> Indemnity for Property Damage</div>
                            <p>The guest agrees to be responsible for any damage caused by the guest, other occupants, or invited visitors during the stay.</p>
                            <p>The guest agrees to cooperate with Swarna Mandapa in resolving any damage-related matter, including providing information, documentation, and payment where required.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>6.</span> Default and Failed Transactions</div>
                            <p>If a payment fails, is declined, or is not completed within the required time, the guest remains responsible for the outstanding amount.</p>
                            <p>Swarna Mandapa may charge reasonable costs related to recovering overdue amounts, including administrative fees, collection costs, legal costs, or other costs permitted by applicable Indonesian law.</p>
                            <p>Overdue balances may be subject to additional charges as stated in the booking confirmation or invoice.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>7.</span> Events Outside Our Control</div>
                            <p>Swarna Mandapa, its owners, managers, employees, and representatives are not responsible for disruptions caused by events outside their reasonable control, including severe weather, natural disasters, government restrictions, utility disruptions, transport interruptions, public health emergencies, or other force majeure events.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>8.</span> Noise and Neighbouring Properties</div>
                            <p>Guests must respect the surrounding environment and neighbouring properties.</p>
                            <p>Noise must be kept at a reasonable level at all times, especially during evening and night hours.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>9.</span> Arrival and Departure</div>
                            <p>Check-in and check-out times must be followed as stated in the booking confirmation.</p>
                            <p>Early check-in or late check-out is only allowed with prior written approval from Swarna Mandapa and may be subject to additional charges.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>10.</span> Parties, Events, and Functions</div>
                            <p>Parties, weddings, commercial events, private functions, or gatherings are not permitted unless approved in writing by Swarna Mandapa before the stay.</p>
                            <p>If an unauthorised event is held, Swarna Mandapa may terminate the stay immediately without refund.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>11.</span> Pets</div>
                            <p>Pets are not allowed unless approved in writing before arrival.</p>
                            <p>If pets are approved, the guest is responsible for any damage, disturbance, odour removal, or additional cleaning required.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>12.</span> Smoking</div>
                            <p>Smoking is not allowed inside the property.</p>
                            <p>If smoking occurs indoors, the guest may be charged for deodorising, cleaning, or restoring the property.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>13.</span> Booking Availability</div>
                            <p>Bookings are accepted in good faith. Swarna Mandapa is not responsible for circumstances outside its control, including property withdrawal, property sale, changes to the property, maintenance issues, or other availability issues.</p>
                            <p>If the booked property becomes unavailable, Swarna Mandapa will make reasonable efforts to offer alternative accommodation or provide a refund according to the applicable booking conditions.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>14.</span> Refund Method</div>
                            <p>Approved refunds will only be returned to the original payment method or to the bank account used for the original transfer.</p>
                            <p>Proof of payment or account ownership may be required.</p>
                        </div>
                        <div class="term-item wide">
                            <div class="term-heading"><span>15.</span> Prohibited Activities</div>
                            <p>Guests must not use the property for any activity that violates Indonesian law or local regulations.</p>
                            <p>Prohibited activities include illegal substances, illegal trade, unauthorised commercial activity, property misuse, disturbance to neighbours, gambling, violence, or any other unlawful conduct.</p>
                            <p>If prohibited activity is found, Swarna Mandapa may require the guest to leave immediately without refund. The guest may also be responsible for cleaning, repair, maintenance, or other related costs.</p>
                        </div>

                        <div class="terms-section-title">Confirmation and Cancellation Policy</div>
                        <div class="term-item wide">
                            <p>Cancellation terms may vary depending on the platform used to make the booking. If the booking is made through a third-party platform such as Airbnb, Booking.com, Agoda, Expedia, or another booking portal, the cancellation policy agreed on that platform will apply.</p>
                            <p>If the booking is made directly with Swarna Mandapa through the website, phone, email, or direct message, the standard cancellation policy below applies.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>1.</span> Direct Booking Cancellation</div>
                            <p>Bookings cancelled in writing more than seven (7) days before the check-in date may be eligible for a refund, excluding bank fees, payment gateway fees, card fees, or other transaction costs.</p>
                            <p>Bookings cancelled within seven (7) days before the check-in date may be charged up to 100% of the total booking value.</p>
                            <p>No refund is provided for early departure, late arrival, or no-show.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>2.</span> Date Changes</div>
                            <p>Date changes may be requested more than seven (7) days before the check-in date.</p>
                            <p>Date changes within seven (7) days before check-in are subject to Swarna Mandapa's approval.</p>
                            <p>If a date change is approved, the original cancellation window may still apply. Additional charges may apply if the new date falls within a higher-rate period.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>3.</span> Deposit and Balance Payment</div>
                            <p>A deposit may be required to confirm the booking.</p>
                            <p>The remaining balance must be paid before arrival according to the payment deadline stated in the booking confirmation.</p>
                            <p>If the balance is not paid by the required deadline, Swarna Mandapa may cancel the booking and apply the relevant cancellation terms.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>4.</span> Grace Period</div>
                            <p>A booking may be cancelled without penalty within three (3) days after confirmation, except for non-refundable transaction fees, payment gateway fees, or bank charges.</p>
                            <p>This grace period does not apply if the check-in date is within the standard cancellation window, unless approved by Swarna Mandapa.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>5.</span> Cancellation by Swarna Mandapa</div>
                            <p>If events outside Swarna Mandapa's control affect the availability of the property, Swarna Mandapa may cancel the booking.</p>
                            <p>In this case, payments already received may be refunded, and the guest will have no further claim against Swarna Mandapa, its owners, managers, employees, or representatives.</p>
                        </div>
                        <div class="term-item wide">
                            <div class="term-heading"><span>6.</span> Force Majeure</div>
                            <p>Force majeure includes events beyond reasonable control, including natural disasters, severe weather, war, terrorism, epidemic, pandemic, government restrictions, border closures, quarantine rules, public emergencies, or other similar events.</p>
                            <p>If Indonesian government restrictions prevent the guest from completing the stay, the guest must notify Swarna Mandapa in writing before the check-in date.</p>
                            <p>Swarna Mandapa may offer a date change, booking credit, or refund depending on the situation, booking source, payment status, and applicable law.</p>
                            <p>Travel advisories, airline cancellations, ferry cancellations, or transport disruption by private companies do not automatically qualify as force majeure.</p>
                        </div>

                        <div class="terms-section-title">Payment Plan Terms and Conditions</div>
                        <div class="term-item wide">
                            <p>Payment plans may be available for selected bookings at Swarna Mandapa. By choosing a payment plan, the guest agrees to these terms.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>1.</span> Eligibility</div>
                            <p>Payment plan users must be at least eighteen (18) years old, provide accurate billing information, use an accepted payment method, and agree to automated or scheduled payments where applicable.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>2.</span> Eligible Bookings</div>
                            <p>Payment plans may be available for bookings made directly through Swarna Mandapa's website or approved booking channel, meeting the minimum booking value shown during checkout, made far enough in advance before arrival, and allowing two (2) or more instalment payments before check-in.</p>
                            <p>Swarna Mandapa may refuse a payment plan for reasons including suspected fraud, previous breach of property rules, incomplete guest information, failed payment history, or other reasonable grounds.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>3.</span> Payment Schedule</div>
                            <p>Payment frequency may include weekly, fortnightly, monthly, or another schedule shown during checkout. Once selected, payment dates may be fixed and may not be changed unless approved by Swarna Mandapa.</p>
                            <p>Full payment must be completed before arrival.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>4.</span> First Payment</div>
                            <p>The first payment will be charged according to the selected payment schedule. For weekly plans, payment is charged weekly from the start date. For fortnightly plans, payment is charged every two weeks from the start date. For monthly plans, payment is charged monthly from the start date.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>5.</span> Final Payment</div>
                            <p>The final payment date and amount will be shown during the booking process. The total balance must be fully paid before check-in.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>6.</span> Payment Fees</div>
                            <p>Payment processing fees, administrative fees, card fees, or payment gateway fees may apply. Any applicable fee will be shown during booking, on the invoice, or through the selected payment provider.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>7.</span> Missed Payment</div>
                            <p>If a payment is missed, declined, or remains outstanding, the guest must contact Swarna Mandapa immediately.</p>
                            <p>If the payment plan remains unpaid for two (2) or more instalments, Swarna Mandapa may cancel the booking. If the booking is cancelled due to missed payments, previous payments may be forfeited according to the applicable cancellation policy and Indonesian law.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>8.</span> Cancelling a Payment Plan</div>
                            <p>A guest may request cancellation of a payment plan in writing. Swarna Mandapa may need to verify the guest's identity before processing the request.</p>
                            <p>Cancellation of a payment plan does not automatically cancel the booking unless stated in writing. If the booking is also cancelled, the standard cancellation policy applies.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>9.</span> Guest Information</div>
                            <p>The guest must provide accurate and complete information, including full name, phone number, email address, billing details, and any identity information required for verification.</p>
                            <p>If the guest's contact or payment details change, the guest must inform Swarna Mandapa as soon as possible.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>10.</span> Payment Authority</div>
                            <p>By choosing a payment plan, the guest authorises Swarna Mandapa or its appointed payment provider to process payments according to the agreed schedule.</p>
                            <p>The guest must ensure that sufficient funds are available on each payment date. If a payment fails, Swarna Mandapa may attempt to process the payment again and may contact the guest regarding the outstanding balance.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>11.</span> Pricing</div>
                            <p>The guest is responsible for the total booking price confirmed at the time of booking. Later discounts, promotions, or price changes do not apply retroactively to an existing confirmed booking.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>12.</span> Privacy</div>
                            <p>Swarna Mandapa stores and uses guest information according to its Privacy Policy and applicable Indonesian data protection laws.</p>
                            <p>Some information may be processed by third-party service providers, including payment gateways, booking platforms, or system providers.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>13.</span> Termination</div>
                            <p>Swarna Mandapa may terminate a payment plan or booking if the guest fails to make payment, provides inaccurate information, breaches these terms, or violates property rules.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>14.</span> Notices</div>
                            <p>Swarna Mandapa may contact the guest by email, phone, WhatsApp, booking platform message, or other written communication.</p>
                            <p>The guest is responsible for keeping contact details active and accurate until the stay is completed.</p>
                        </div>
                        <div class="term-item">
                            <div class="term-heading"><span>15.</span> Governing Law</div>
                            <p>These terms are governed by the laws of the Republic of Indonesia.</p>
                            <p>Any dispute will first be handled through direct communication between the guest and Swarna Mandapa. If the dispute cannot be resolved directly, it may be referred to the appropriate dispute resolution process or competent court in Indonesia.</p>
                        </div>
                        <div class="terms-section-title">Contact</div>
                        <div class="term-item wide">
                            <p>For questions, booking changes, cancellations, payment issues, or disputes, guests must contact Swarna Mandapa through the official contact details listed on the website or booking confirmation.</p>
                        </div>
                    </div>
                </div>
                <div class="terms-agreement">
                    <input type="checkbox" id="termsOfRent" name="terms_of_rent" value="1" form="confirmationForm" {{ old('terms_of_rent') ? 'checked' : '' }} required>
                    <div>
                        <label for="termsOfRent">I have read and agree to the Terms of Rent.</label>
                        <small>Required before proceeding to booking confirmation and payment.</small>
                        @error('terms_of_rent')
                            <div class="text-danger mt-1" style="font-size:.78rem">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-3 fade-up confirmation-actions">
                <a href="{{ route('booking.form') }}" class="btn btn-gold-outline w-50 d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-left me-2"></i>Back to Edit
                </a>
                <form action="{{ route('booking.confirmation.store') }}" method="POST" class="w-50" id="confirmationForm">
                    @csrf
                    <button type="submit" class="btn btn-gold btn-gold-lg w-100 btn-confirm-disabled" id="confirmBookingButton" disabled>
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
        <div class="col-lg-4 order-1 order-lg-2 booking-confirm-price">
            <div class="price-summary-card fade-up" style="position:sticky;top:1.5rem">
                <div class="price-summary-header">
                    <h6 class="mb-0">Price Breakdown</h6>
                    <small>All amounts in IDR</small>
                </div>
                <div class="price-summary-body">
                    @php
                        $nights        = 0;
                        if (!empty($booking['check_in']) && !empty($booking['check_out'])) {
                            $nights = (new DateTime($booking['check_in']))->diff(new DateTime($booking['check_out']))->days;
                        }
                        $pricePerNight = $booking['price_per_night'] ?? 0;
                        $base          = $booking['subtotal']        ?? ($pricePerNight * $nights);
                        $discount      = $booking['discount_amount'] ?? 0;
                        $total         = $booking['total_price']     ?? ($base - $discount);
                        $promoCode     = $booking['promo_code']      ?? null;
                        $seasonGroups = collect($booking['nightly_breakdown'] ?? [])
                            ->groupBy(fn ($night) => ($night['label'] ?? 'Villa Rate').'|'.($night['price'] ?? 0))
                            ->map(function ($items) {
                                $first = $items->first();
                                return [
                                    'label' => $first['label'] ?? 'Villa Rate',
                                    'price' => (int) ($first['price'] ?? 0),
                                    'nights' => $items->count(),
                                ];
                            });
                    @endphp
                    <div class="price-row">
                        <span>Rp {{ number_format($pricePerNight, 0, ',', '.') }} × {{ $nights }} nights</span>
                        <span>Rp {{ number_format($base, 0, ',', '.') }}</span>
                    </div>
                    @if($seasonGroups->isNotEmpty())
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:-.4rem;margin-bottom:.4rem">
                            @foreach($seasonGroups as $group)
                                <div>{{ $group['label'] }}: {{ $group['nights'] }} night{{ $group['nights'] > 1 ? 's' : '' }} &times; Rp {{ number_format($group['price'], 0, ',', '.') }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if($discount > 0)
                    <div class="price-row" style="color:var(--success)">
                        <span><i class="bi bi-tag me-1"></i>Promo ({{ $promoCode }})</span>
                        <span>— Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
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

<div class="modal fade terms-modal" id="termsOfRentModal" tabindex="-1" aria-labelledby="termsOfRentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="terms-eyebrow mb-1">Terms of Rent</div>
                    <h5 class="modal-title" id="termsOfRentModalLabel">Swarna Mandapa Rental Agreement</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="termsOfRentModalBody"></div>
            <div class="modal-footer" style="border-top:1px solid #EBE4D5;background:var(--cream)">
                <button type="button" class="btn btn-gold terms-done-button" id="termsDoneButton" data-bs-dismiss="modal" disabled>Done Reading</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const termsCheckbox = document.getElementById('termsOfRent');
        const confirmButton = document.getElementById('confirmBookingButton');
        const termsContent = document.getElementById('termsOfRentContent');
        const termsModalBody = document.getElementById('termsOfRentModalBody');
        const termsModal = document.getElementById('termsOfRentModal');
        const termsDoneButton = document.getElementById('termsDoneButton');
        const termsModalInstance = termsModal ? new bootstrap.Modal(termsModal) : null;

        if (termsContent && termsModalBody) {
            termsModalBody.appendChild(termsContent);
        }

        if (!termsCheckbox || !confirmButton) return;

        const syncConfirmState = () => {
            confirmButton.disabled = !termsCheckbox.checked;
        };

        termsCheckbox.addEventListener('click', function (event) {
            if (!termsCheckbox.checked) {
                syncConfirmState();
                return;
            }

            event.preventDefault();
            termsCheckbox.checked = false;
            syncConfirmState();
            termsModalInstance?.show();
        });
        syncConfirmState();

        const hasReadToEnd = () => {
            if (!termsContent) return false;

            return termsContent.scrollTop + termsContent.clientHeight >= termsContent.scrollHeight - 8;
        };

        const syncDoneState = () => {
            if (!termsDoneButton) return;
            termsDoneButton.disabled = !hasReadToEnd();
        };

        if (termsContent && termsDoneButton) {
            termsContent.addEventListener('scroll', syncDoneState);
            termsContent.addEventListener('wheel', function (event) {
                event.preventDefault();
                termsContent.scrollTop += event.deltaY;
                syncDoneState();
            }, { passive: false });
            syncDoneState();
        }

        if (termsModal) {
            termsModal.addEventListener('shown.bs.modal', function () {
                document.body.classList.add('terms-modal-lock');
                if (termsContent) {
                    termsContent.scrollTop = 0;
                }
                syncDoneState();
            });

            termsModal.addEventListener('hidden.bs.modal', function () {
                document.body.classList.remove('terms-modal-lock');
            });

            termsModal.addEventListener('wheel', function (event) {
                if (!termsContent || termsContent.contains(event.target)) return;
                event.preventDefault();
            }, { passive: false });

            termsModal.addEventListener('touchmove', function (event) {
                if (!termsContent || termsContent.contains(event.target)) return;
                event.preventDefault();
            }, { passive: false });
        }

        if (termsDoneButton) {
            termsDoneButton.addEventListener('click', function () {
                if (termsDoneButton.disabled) return;

                termsCheckbox.checked = true;
                syncConfirmState();
            });
        }
    });
</script>
@endpush
