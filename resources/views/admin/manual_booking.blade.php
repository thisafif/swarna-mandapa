@extends('layouts.admin')

@push('styles')
<style>
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        color: var(--text-dark);
        margin-bottom: 2.5rem;
        text-align: center;
    }
    
    .page-title em {
        font-style: italic;
        color: var(--brand-gold-dark);
    }

    .form-card {
        background-color: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }

    .form-body {
        padding: 2.5rem;
    }

    .form-footer {
        background-color: #F8F6F2;
        padding: 1.5rem 2.5rem;
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .info-alert {
        background-color: #FDFBF7; /* Very light cream/gold tint */
        border: 1px solid #EBE4D5;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.8rem;
        color: #7A6953;
        margin-bottom: 2rem;
    }

    .info-alert i {
        font-size: 1.2rem;
        color: var(--brand-gold-dark);
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .form-control {
        background-color: #FFFFFF;
        border: 1px solid #EBEBEB;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        color: var(--text-dark);
        box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--brand-gold);
        box-shadow: 0 0 0 3px rgba(184, 146, 74, 0.1);
    }

    .btn-submit {
        background-color: var(--brand-gold-dark);
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        padding: 0.85rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #8A642B;
    }

    /* Floating row gaps */
    .row-form {
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }
        .form-body {
            padding: 1.5rem;
        }
        .form-footer {
            padding: 1.5rem;
            justify-content: stretch;
        }
        .btn-submit {
            width: 100%;
        }
        .info-alert {
            align-items: flex-start;
        }
    }

    /* Modal Styling */
    .success-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .success-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .success-modal-content {
        background: #fff;
        border-radius: 12px;
        padding: 2.5rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateY(20px);
        transition: transform 0.3s;
    }
    .success-modal-overlay.active .success-modal-content {
        transform: translateY(0);
    }
    .success-icon {
        width: 60px;
        height: 60px;
        background: #F0FDF4; /* light green */
        color: #16A34A; /* dark green */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin: 0 auto 1.5rem;
    }
    .success-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.2rem;
    }
    .reservation-details {
        background: #F8F6F2;
        border: 1px dashed #D6D6D6;
        border-radius: 8px;
        padding: 1.25rem;
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 2rem;
        text-align: left;
    }
    .reservation-details strong {
        color: var(--text-dark);
        font-weight: 600;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid #D6D6D6;
        color: #555;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.2s;
    }
    .btn-outline:hover {
        background: #F5F5F5;
        color: var(--text-dark);
    }

</style>
@endpush

@section('content')
    <h1 class="page-title">Self-Manual <em>Booking</em></h1>

    @if(session('success'))
        <div class="info-alert" style="background:#E9F7EF; border-color:#D1E7DD; color:#0F5132;">
            <i class="bi bi-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="info-alert" style="background:#FFE8E8; border-color:#F5A5A5; color:#842029;">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <ul style="margin:0; padding-left:1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="form-card">
        <div class="form-body">
            <!-- Warning Box -->
            <div class="info-alert">
                <i class="bi bi-info-circle"></i>
                <div>System will automatically check date availability and save the booking to the database.</div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.manual_booking.submit') }}" method="POST">
                @csrf
                
                <div class="row row-form">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" name="check_in" value="{{ old('check_in') }}" required>
                    </div>
                    <div class="col-md-6">
                        <!-- Corrected label from mockup (it said Check-in Date twice) -->
                        <label class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" name="check_out" value="{{ old('check_out') }}" required>
                    </div>
                </div>

                <div class="row row-form">
                    <div class="col-12">
                        <label class="form-label">Full Guest Name</label>
                        <input type="text" class="form-control" name="guest_name" value="{{ old('guest_name') }}" placeholder="e.g. John Doe" required>
                    </div>
                </div>

                <div class="row row-form mb-0">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Phone / WhatsApp</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="e.g. +62 812..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Number of Guests</label>
                        <input type="number" class="form-control" name="guests" value="{{ old('guests') }}" placeholder="1" min="1" max="10" required>
                    </div>
                </div>

                <div class="row row-form">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="e.g. guest@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <input type="text" class="form-control" name="notes" value="{{ old('notes') }}" placeholder="Optional notes">
                    </div>
                </div>

        </div>
        
        <div class="form-footer">
            <button type="submit" class="btn-submit">Check & Make a reservation</button>
        </div>
        </form>
    </div>

@endsection
