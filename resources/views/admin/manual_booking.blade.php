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

    <div class="form-card">
        <div class="form-body">
            <!-- Warning Box -->
            <div class="info-alert">
                <i class="bi bi-info-circle"></i>
                <div>System will automatically check date availability and block the calendar once you save this reservation.</div>
            </div>

            <!-- Form -->
            <form action="#" method="POST">
                @csrf
                
                <div class="row row-form">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" name="check_in" required>
                    </div>
                    <div class="col-md-6">
                        <!-- Corrected label from mockup (it said Check-in Date twice) -->
                        <label class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" name="check_out" required>
                    </div>
                </div>

                <div class="row row-form">
                    <div class="col-12">
                        <label class="form-label">Full Guest Name</label>
                        <input type="text" class="form-control" name="guest_name" placeholder="e.g. John Doe" required>
                    </div>
                </div>

                <div class="row row-form mb-0">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Phone / WhatsApp</label>
                        <input type="text" class="form-control" name="phone" placeholder="e.g. +62 812..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Number of Guests</label>
                        <input type="number" class="form-control" name="guests" placeholder="1" min="1" max="10" required>
                    </div>
                </div>

        </div>
        
        <div class="form-footer">
            <button type="button" class="btn-submit" onclick="showModal()">Check & Make a reservation</button>
        </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="success-modal-overlay" id="successModal">
        <div class="success-modal-content">
            <div class="success-icon">
                <i class="bi bi-check"></i>
            </div>
            <h3 class="success-title">Reservation Created!</h3>
            
            <div class="reservation-details">
                <div class="mb-2"><strong>Reference:</strong> SWM-2026-000102</div>
                <div class="mb-2"><strong>Guest:</strong> <span id="modalGuestName">John Doe</span></div>
                <div><strong>Timeline:</strong> <span id="modalCheckIn">...</span> &mdash; <span id="modalCheckOut">...</span></div>
            </div>

            <!-- In a real app, this goes to the booking list -->
            <button class="btn-submit" style="width:100%; margin-bottom:0.75rem" onclick="location.href='{{ route('admin.dashboard') }}'">Go to Booking List</button>
            <button class="btn-outline" onclick="closeModal()">Create New Booking</button>
        </div>
    </div>

    <script>
        function showModal() {
            // Get form values for realistic interaction
            const guestName = document.querySelector('input[name="guest_name"]').value || 'A New Guest';
            const checkIn = document.querySelector('input[name="check_in"]').value || 'N/A';
            const checkOut = document.querySelector('input[name="check_out"]').value || 'N/A';
            
            document.getElementById('modalGuestName').textContent = guestName;
            document.getElementById('modalCheckIn').textContent = checkIn;
            document.getElementById('modalCheckOut').textContent = checkOut;
            
            document.getElementById('successModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('successModal').classList.remove('active');
            // Empty the form so they can type a new one
            document.querySelector('form').reset();
        }
    </script>
@endsection
