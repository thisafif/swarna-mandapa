@extends('layouts.app')
@section('title', 'Booking Confirmed — Swarna Mandapa')

@push('styles')
<style>
    /* Main background WHITE */
    body { background: #FDFBF7; }
    .done-container { max-width: 780px; margin: 0 auto; padding: 2rem 1rem; }

    /* Step Indicator - sama seperti invoice.blade */
    .step-wrap { display:flex; align-items:center; justify-content:center; margin-bottom:2.5rem }
    .step-item { display:flex; flex-direction:column; align-items:center; gap:.3rem }
    .step-line { width:60px; height:2px; background:var(--border); margin:0 .25rem; margin-bottom:1.2rem }
    .step-dot { width:30px; height:30px; border-radius:50%; border:2px solid var(--border); background:var(--white); display:grid; place-items:center; font-size:.72rem; font-weight:600; color:var(--text-muted) }
    .step-item.active .step-dot { border-color:var(--gold); background:var(--gold); color:#fff }
    .step-item.done .step-dot { border-color:var(--success); background:var(--success); color:#fff }
    .step-lbl { font-size:.65rem; letter-spacing:.07em; color:var(--text-muted); text-transform:uppercase }
    .step-item.active .step-lbl { color:var(--gold); font-weight:600 }
    .step-item.done .step-lbl { color:var(--success) }

    /* Premium Success Message */
    .success-banner {
        background: #FFFFFF;
        border: 1px solid rgba(201, 169, 110, 0.2);
        border-radius: 12px;
        padding: 3.5rem 2rem;
        text-align: center;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }

    .success-icon-wrapper {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 0 10px rgba(76, 175, 80, 0.1);
        animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s forwards;
        opacity: 0;
        transform: scale(0.5);
    }

    @keyframes scaleIn {
        to { opacity: 1; transform: scale(1); }
    }

    .success-icon {
        font-size: 40px;
        color: #2E7D32;
    }

    .success-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 32px;
        font-weight: 600;
        color: #C9A96E; /* Brand Gold */
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }
    .success-title em {
        font-style: italic;
        font-weight: 500;
        color: #333;
    }
    .success-message {
        font-size: 15px;
        color: #555;
        letter-spacing: 0.02em;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Card */
    .panel {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    /* Buttons */
    .btn-group { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem; }
    .btn { 
        padding: 1rem; 
        border: none; 
        border-radius: 8px; 
        font-size: 13px; 
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

    /* Print */
    @media print {
        body { background: white; }
        .done-container { padding: 0; }
        .success-banner, .btn-group { display: none !important; }
        .panel { box-shadow: none; border: none; padding: 0; margin: 0; }
    }

    @media (max-width: 768px) {
        .btn-group { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

@php
    $bookingCode   = $booking->booking_code ?? 'SWM-XXXXXX';
    $guestName     = isset($booking->first_name)
                      ? trim($booking->first_name . ' ' . $booking->last_name)
                      : 'Guest';
@endphp

<div class="done-container">

    <!-- Step Indicator (All Done) -->
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
        <div class="step-item done">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">Payment</div>
        </div>
        <div class="step-line" style="background:var(--success)"></div>
        <div class="step-item done">
            <div class="step-dot"><i class="bi bi-check-lg" style="font-size:.65rem"></i></div>
            <div class="step-lbl">Done</div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="success-banner">
        <div class="success-icon-wrapper">
            <i class="bi bi-check-lg success-icon"></i>
        </div>
        <div class="success-title">Thank You, <em>{{ $guestName }}</em></div>
        <div class="success-message">Your booking has been confirmed and payment received.</div>
    </div>

    <!-- Invoice (Minimal - No Header) -->
    <div class="panel">
        @include('booking.invoice-minimal', ['booking' => $booking])
    </div>

    <!-- Action Buttons -->
    <div class="btn-group">
        <a href="{{ route('booking.invoice.pdf') }}?code={{ $booking->booking_code }}" 
   target="_blank"
   class="btn btn-secondary">
    <i class="bi bi-download"></i> Download Invoice
</a>
        <a href="{{ route('booking.form') }}" class="btn btn-primary">
            <i class="bi bi-house-heart"></i> New Booking
        </a>
    </div>

</div>


@endsection
