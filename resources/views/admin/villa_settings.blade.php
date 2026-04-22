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
        background-color: #F8F6F2; /* Creamy background from mockup */
        padding: 1.5rem 2.5rem;
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .info-alert {
        background-color: #FDFBF7;
        border: 1px solid #EBE4D5;
        border-radius: 8px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-size: 0.85rem;
        color: #7A6953;
        margin-bottom: 2rem;
    }

    .info-alert i {
        font-size: 1.2rem;
        color: var(--brand-gold-dark);
    }

    .section-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #8c7144; /* Match darker gold mockup text */
        margin-bottom: 1.25rem;
        margin-top: 2rem;
        border-bottom: 1px solid #EBEBEB;
        padding-bottom: 0.5rem;
    }

    .section-title:first-of-type {
        margin-top: 0;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #888;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        background-color: #FFFFFF;
        border: 1px solid #EBEBEB;
        border-radius: 12px; /* Smoother border radius based on mockup */
        padding: 0.8rem 1rem;
        font-size: 0.9rem;
        color: var(--text-dark);
        box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        transition: all 0.2s;
        width: 100%;
        display: block;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23888' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px 10px;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--brand-gold);
        box-shadow: 0 0 0 3px rgba(184, 146, 74, 0.1);
    }

    .btn-submit {
        background-color: #A98C59; /* Soft gold pill button matching mockup */
        color: #FFFFFF;
        border: none;
        border-radius: 50px;
        padding: 0.6rem 2.25rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
    }

    .btn-submit:hover {
        background-color: #8A642B;
    }

    .btn-submit:active {
        transform: scale(0.97);
    }

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
            border-radius: 12px;
            padding: 0.8rem;
        }
        .info-alert {
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
    <h1 class="page-title">Configuration <em>List</em></h1>

    <div class="form-card">
        <form action="#" method="POST">
            @csrf
            
            <div class="form-body">
                @if(session('success'))
                    <div style="background-color: #Edf7ed; color: #1E4620; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; display:flex; align-items:center; gap:0.75rem;">
                        <i class="bi bi-check-circle-fill" style="font-size: 1.1rem;"></i> 
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Warning Box -->
                <div class="info-alert">
                    <i class="bi bi-info-circle"></i>
                    <div>You can manage base pricing and promotional campaigns here. Be Careful!</div>
                </div>

                <!-- Base Price Section -->
                <h3 class="section-title">Base Price</h3>
                <div class="row row-form">
                    <div class="col-12">
                        <label class="form-label">Price per Night</label>
                        <input type="text" class="form-control" name="base_price" placeholder="" required>
                    </div>
                </div>

                <!-- Active Promos Section -->
                <h3 class="section-title">Active Promos</h3>
                <div class="row row-form">
                    <div class="col-12">
                        <label class="form-label">Promo Name</label>
                        <input type="text" class="form-control" name="promo_name" placeholder="" required>
                    </div>
                </div>

                <div class="row row-form mb-0">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" class="form-control" name="promo_discount" placeholder="" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Promo Status</label>
                        <select class="form-select" name="promo_status">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Light Promo Logic Preview -->
                <div class="p-3 mt-4" style="background-color: #FAFAFA; border: 1px dashed #D6D6D6; border-radius: 12px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size: 0.85rem; color: #666; font-weight:600;">Estimated Final Price:</span>
                    <div id="finalPriceDisplay" style="font-size: 1.5rem; font-weight: 700; color: var(--brand-gold-dark);">Rp 0</div>
                </div>

            </div>
            
            <div class="form-footer">
                <button type="submit" class="btn-submit">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Light Promo Logic Interactivity -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const basePriceInput = document.querySelector('input[name="base_price"]');
            const discountInput = document.querySelector('input[name="promo_discount"]');
            const finalPriceDisplay = document.getElementById('finalPriceDisplay');

            function calculateFinalPrice() {
                let basePrice = parseFloat(basePriceInput.value.replace(/[^0-9.-]+/g,"")) || 0;
                let discount = parseFloat(discountInput.value) || 0;
                
                if (discount > 100) discount = 100;
                if (discount < 0) discount = 0;

                let finalPrice = basePrice - (basePrice * (discount / 100));
                
                finalPriceDisplay.textContent = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    maximumFractionDigits: 0 
                }).format(finalPrice);
            }

            basePriceInput.addEventListener('input', calculateFinalPrice);
            discountInput.addEventListener('input', calculateFinalPrice);
        });
    </script>
@endsection
