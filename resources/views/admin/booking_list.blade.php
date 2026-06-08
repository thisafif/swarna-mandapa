@extends('layouts.admin')

@push('styles')
<style>
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        text-align: center;
    }
    .page-title em {
        font-style: italic;
        color: var(--brand-gold-dark);
    }
    .page-subtitle {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #555555; /* Higher contrast than mockup */
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 3rem;
    }

    .table-card {
        background-color: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table-toolbar {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #EBEBEB;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        max-width: 400px;
        flex-grow: 1;
    }
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #555;
    }
    .search-input {
        width: 100%;
        padding: 0.75rem 7.2rem 0.75rem 2.8rem;
        border-radius: 50px;
        border: 1px solid #C0C0C0; /* Higher contrast border */
        background-color: #FAFAFA;
        font-size: 0.9rem;
        color: #333;
    }
    .search-input:focus {
        outline: none;
        border-color: var(--brand-gold);
        background-color: #FFFFFF;
    }

    .search-button {
        position: absolute;
        right: 0.35rem;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        border-radius: 999px;
        background: var(--brand-gold-dark);
        color: #FFFFFF;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        padding: 0.55rem 1rem;
        min-width: 92px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .search-button:hover {
        background: #8A642B;
    }

    .filter-box {
        min-width: 200px;
    }
    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 50px;
        border: 1px solid #C0C0C0; /* Higher contrast border */
        background-color: #FAFAFA;
        font-size: 0.9rem;
        color: #333;
        appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23333333%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 1rem top 50%;
        background-size: 0.65rem auto;
    }
    .filter-select:focus {
        outline: none;
        border-color: var(--brand-gold);
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .booking-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .booking-table th {
        font-size: 0.75rem;
        color: #333333; /* High contrast */
        font-weight: 700;
        text-transform: uppercase;
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid #EBEBEB;
        background-color: #FDFDFD;
    }

    .booking-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #EBEBEB;
        color: #222222; /* High contrast */
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .col-no { width: 5%; color: #555; }
    .col-code { font-weight: 600; width: 15%; }
    .col-guest { font-weight: 700; color: #111; width: 22%; }
    .col-date { color: #555; width: 18%; font-weight: 500; }
    .col-status { width: 15%; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.05em; color: #444; }
    .col-created { color: #555; width: 15%; }
    .col-action { width: 10%; text-align: right; }

    .action-link {
        font-weight: 700;
        color: var(--brand-gold-dark);
        text-decoration: none;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        transition: color 0.2s;
    }
    .action-link:hover {
        color: #8A642B;
    }

    .table-footer {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #FFFFFF;
        font-size: 0.85rem;
        color: #555;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .page-item {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        border: 1px solid #EBEBEB;
        transition: all 0.2s;
    }
    
    .page-item:hover {
        background-color: #F5F5F5;
    }

    .page-item.disabled {
        color: #A0A0A0;
        cursor: not-allowed;
        background-color: #FAFAFA;
    }

    .page-item.active {
        border-color: #D6D6D6;
        background-color: #FFFFFF;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        color: var(--brand-gold-dark);
    }

    /* Offcanvas Detail */
    .detail-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1040;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
        display: none;
    }
    .detail-backdrop.show {
        display: block;
        opacity: 1;
        pointer-events: auto;
    }
    .detail-panel {
        position: fixed;
        top: 0; right: -420px;
        width: 100%;
        max-width: 420px;
        height: 100vh;
        background: #FFF;
        z-index: 1050;
        box-shadow: -5px 0 25px rgba(0,0,0,0.05);
        transition: right 0.3s ease;
        display: flex;
        flex-direction: column;
        visibility: hidden;
    }
    .detail-panel.show {
        right: 0;
        visibility: visible;
    }
    .dp-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #EBEBEB;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #FAFAFA;
    }
    .dp-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brand-gold-dark);
        margin: 0;
    }
    .dp-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #888;
        cursor: pointer;
        padding: 0.5rem;
    }
    .dp-body {
        padding: 2rem;
        overflow-y: auto;
        flex-grow: 1;
    }
    .dp-section {
        margin-bottom: 2rem;
    }
    .dp-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
    }
    .dp-value {
        font-size: 0.95rem;
        color: #222;
        font-weight: 500;
        line-height: 1.5;
    }
    .dp-value-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .dp-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #Edf7ed;
        color: #1E4620;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    .dp-contact-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .dp-contact-button {
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.65rem 0.85rem;
        transition: background-color 0.2s;
    }
    .dp-contact-button i {
        margin-right: 0.35rem;
    }
    .dp-contact-whatsapp {
        background-color: #E7F7ED;
        color: #137A3F;
    }
    .dp-contact-whatsapp:hover {
        background-color: #D4F0DF;
    }
    .dp-contact-email {
        background-color: #FDFBF7;
        color: var(--brand-gold-dark);
        border: 1px solid #EBE4D5;
    }
    .dp-contact-email:hover {
        background-color: #F7F0E3;
    }
    .dp-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .dp-action-button {
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        padding: 0.75rem 1rem;
        transition: background-color 0.2s;
    }
    .dp-action-button i {
        margin-right: 0.35rem;
    }
    .dp-action-download {
        grid-column: 1 / -1;
        background-color: var(--brand-gold-dark);
        color: #FFFFFF;
    }
    .dp-action-download:hover {
        background-color: #8A642B;
    }
    .dp-action-edit {
        background-color: #E8E8E8;
        color: #333;
    }
    .dp-action-edit:hover {
        background-color: #D8D8D8;
    }
    .dp-action-delete {
        background-color: #FFE5E5;
        color: #E05A5A;
    }
    .dp-action-delete:hover {
        background-color: #FFD0D0;
    }

    /* Edit Modal */
    #editBackdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1045;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
        display: none;
    }
    #editBackdrop.show {
        display: block;
        opacity: 1;
        pointer-events: auto;
    }
    #editModal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: #FFF;
        border-radius: 12px;
        z-index: 1055;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        max-height: 90vh;
        overflow-y: auto;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
        display: none;
    }
    #editModal.show {
        display: block;
        opacity: 1;
        pointer-events: auto;
        transform: translate(-50%, -50%) scale(1);
    }

    /* Form Controls */
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #DDD;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--brand-gold);
        box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.1);
    }
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.9rem;
        color: #222;
    }
    .mb-3 {
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.8rem;
        }
        .page-subtitle {
            margin-bottom: 2rem;
        }
        .table-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .search-box {
            max-width: 100%;
        }
        .table-footer {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
            align-items: center;
        }
    }
</style>
@endpush

@section('content')
    <h1 class="page-title">Booking <em>List</em></h1>
    <div class="page-subtitle">Recent Transaction</div>

    <div class="table-card">
        <!-- Toolbar -->
        <form method="GET" action="{{ route('admin.booking_list') }}" class="table-toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="q" value="{{ request()->query('q') }}" class="search-input" placeholder="Search by guest name or code" onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">
                <button type="submit" class="search-button">Search</button>
            </div>
            <div class="filter-box">
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">Filter by Status</option>
                    <option value="confirmed" {{ request()->query('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="pending" {{ request()->query('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="declined" {{ request()->query('status') === 'declined' ? 'selected' : '' }}>Declined</option>
                </select>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="booking-table">
                <thead>
                    <tr>
                        <th class="col-no text-start">NO</th>
                        <th class="col-code text-start">CODE TRX</th>
                        <th class="col-guest text-start">GUEST</th>
                        <th class="col-date text-start">DATE</th>
                        <th class="col-status text-start">PAYMENT STATUS</th>
                        <th class="col-created text-start">CREATED AT</th>
                        <th class="col-action text-end">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $index => $booking)
                        <tr data-booking='@json($booking)' onclick="openDetail(this); return false;">
                            <td class="col-no">{{ str_pad(($bookings->firstItem() ?? 0) + $index, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="col-code">{{ $booking->booking_code }}</td>
                            <td class="col-guest">{{ $booking->first_name }} {{ $booking->last_name }}</td>
                            <td class="col-date">{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</td>
                            <td class="col-status">{{ $booking->status }}</td>
                            <td class="col-created">{{ $booking->created_at->format('M d, Y H:i') }}</td>
                            <td class="col-action">
                                <a href="#" class="action-link">DETAIL</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding:2rem; color:#666;">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="table-footer">
            <div class="entries-info">
                Showing {{ $bookings->firstItem() ?? 0 }}-{{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries
            </div>
            <div class="pagination">
                @if($bookings->onFirstPage())
                    <span class="page-item disabled"><i class="bi bi-chevron-left mt-1" style="font-size:0.7rem"></i></span>
                @else
                    <a href="{{ $bookings->previousPageUrl() }}" class="page-item" aria-label="Previous page"><i class="bi bi-chevron-left mt-1" style="font-size:0.7rem"></i></a>
                @endif

                @php
                    $bookingStartPage = max(1, $bookings->currentPage() - 2);
                    $bookingEndPage = min($bookings->lastPage(), $bookings->currentPage() + 2);
                @endphp

                @for($page = $bookingStartPage; $page <= $bookingEndPage; $page++)
                    <a href="{{ $bookings->url($page) }}" class="page-item {{ $bookings->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                @endfor

                @if($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}" class="page-item" aria-label="Next page"><i class="bi bi-chevron-right mt-1" style="font-size:0.7rem"></i></a>
                @else
                    <span class="page-item disabled"><i class="bi bi-chevron-right mt-1" style="font-size:0.7rem"></i></span>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Backdrop & Panel (Offcanvas) -->
    <div class="detail-backdrop" id="detailBackdrop" onclick="closeDetail()"></div>
    <div class="detail-panel" id="detailPanel">
        <div class="dp-header">
            <h3 class="dp-title">Booking Detail</h3>
            <button class="dp-close" onclick="closeDetail()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="dp-body">
            <div class="dp-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="dp-label mb-0">Booking Ref</div>
                    <div class="dp-status" id="dp-status">CONFIRMED</div>
                </div>
                <div class="dp-value" id="dp-booking-code" style="font-size:1.2rem; color:var(--brand-gold-dark); font-weight:700;">SWM-2026-000101</div>
            </div>

            <hr style="border-color:#EBEBEB; margin: 1.5rem 0;">

            <div class="dp-section">
                <div class="dp-label">Guest Information</div>
                <div class="dp-value">
                    <strong style="font-size: 1.05rem;" id="dp-guest-name">Guest Name</strong><br>
                    <span style="color:#666; font-size:0.85rem; display:inline-block; margin-top:0.4rem;">
                        <i class="bi bi-whatsapp" style="margin-right:0.3rem"></i> <span id="dp-phone">+62 812-3456-7890</span><br>
                        <i class="bi bi-envelope" style="margin-right:0.3rem"></i> <span id="dp-email">apip@example.com</span>
                    </span>
                </div>
                <div class="dp-contact-actions">
                    <button type="button" class="btn dp-contact-button dp-contact-whatsapp" onclick="contactCustomerWhatsApp()">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </button>
                    <button type="button" class="btn dp-contact-button dp-contact-email" onclick="contactCustomerEmail()">
                        <i class="bi bi-envelope"></i> Email
                    </button>
                </div>
            </div>

            <div class="dp-section">
                <div class="dp-label">Notes</div>
                <div class="p-3" style="background:#FDFBF7; border:1px solid #EBE4D5; border-radius:8px; color:#555; font-size:0.9rem; line-height:1.6; white-space:pre-line;" id="dp-notes">-</div>
            </div>

            <div class="dp-section">
                <div class="dp-label">Stay Details</div>
                <div class="dp-value-group mb-3">
                    <div>
                        <div style="font-size:0.75rem; color:#888; font-weight: 600; margin-bottom: 0.2rem;">Check-in</div>
                        <div style="font-weight:700; color:#222;" id="dp-check-in">26 Feb 2026</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:#888; font-weight: 600; margin-bottom: 0.2rem;">Check-out</div>
                        <div style="font-weight:700; color:#222;" id="dp-check-out">28 Feb 2026</div>
                    </div>
                </div>
                <div class="p-3" style="background: #FDFBF7; border: 1px solid #EBE4D5; border-radius: 8px;">
                    <div style="font-size:0.75rem; color:#7A6953; font-weight: 600; margin-bottom: 0.2rem;">Guests</div>
                    <div style="font-weight:600; color:#222; font-size: 0.9rem;"><span id="dp-guests">1</span> Guest(s)</div>
                    <div style="font-size:0.8rem; color:#666; margin-top: 0.4rem;"><i class="bi bi-info-circle"></i> Entire Villa (4 Bedrooms), up to 8 guests max</div>
                </div>
            </div>

            <hr style="border-color:#EBEBEB; margin: 1.5rem 0;">

            <div class="dp-section">
                <div class="dp-label">Payment Breakdown</div>
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:0.9rem; color:#555;">Average Price/Night</span>
                    <span style="font-size:0.9rem; font-weight:600;" id="dp-price-per-night">$0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:0.9rem; color:#555;">Nights</span>
                    <span style="font-size:0.9rem; font-weight:600;" id="dp-nights">0</span>
                </div>
                <div id="dp-season-breakdown" style="display:none;background:#FDFBF7;border:1px solid #EBE4D5;border-radius:8px;padding:.75rem;margin:.75rem 0;font-size:.82rem;color:#555"></div>
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:0.9rem; color:#555;">Subtotal</span>
                    <span style="font-size:0.9rem; font-weight:600;" id="dp-subtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:0.9rem; color:#555;">Discount</span>
                    <span style="font-size:0.9rem; font-weight:600;" id="dp-discount">$0</span>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-3" style="border-top: 1px dashed #DDD;">
                    <span style="font-size:0.9rem; color:#222; font-weight: 600;">Total Amount</span>
                    <span style="font-size:1.1rem; font-weight:700; color:var(--brand-gold-dark);" id="dp-total-price">$0</span>
                </div>
            </div>

            <hr style="border-color:#EBEBEB; margin: 1.5rem 0;">

            <!-- Action Buttons -->
            <div class="dp-section">
                <div class="dp-actions">
                    <button type="button" class="btn dp-action-button dp-action-download" onclick="downloadInvoice()">
                        <i class="bi bi-download"></i> Download Invoice
                    </button>
                    <button type="button" class="btn dp-action-button dp-action-edit" onclick="openEditModal()">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    <button type="button" class="btn dp-action-button dp-action-delete" onclick="deleteBooking()">
                        <i class="bi bi-trash3"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="detail-backdrop" id="editBackdrop" onclick="closeEditModal()"></div>
    <div class="detail-panel" id="editModal" style="width: 500px; max-width: 90vw;">
        <div class="dp-header">
            <h3 class="dp-title">Edit Booking</h3>
            <button class="dp-close" onclick="closeEditModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="dp-body">
            <form id="editBookingForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editBookingId" name="id">
                
                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">First Name</label>
                    <input type="text" id="editFirstName" name="first_name" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Last Name</label>
                    <input type="text" id="editLastName" name="last_name" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Email</label>
                    <input type="email" id="editEmail" name="email" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Phone</label>
                    <input type="tel" id="editPhone" name="phone" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Check-in Date</label>
                    <input type="date" id="editCheckIn" name="check_in" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Check-out Date</label>
                    <input type="date" id="editCheckOut" name="check_out" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Guests</label>
                    <input type="number" id="editGuests" name="guests" min="1" max="10" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Price Per Night</label>
                    <input type="number" id="editPricePerNight" name="price_per_night" step="0.01" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Discount Amount</label>
                    <input type="number" id="editDiscountAmount" name="discount_amount" step="0.01" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Status</label>
                    <select id="editStatus" name="status" class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD;" required>
                        <option value="PENDING">PENDING</option>
                        <option value="CONFIRMED">CONFIRMED</option>
                        <option value="CANCELLED">CANCELLED</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">View Notes</label>
                    <textarea id="editViewNotes" class="form-control" rows="4" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #DDD; background:#FDFBF7; color:#555;" readonly></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem;">
                    <button type="button" class="btn" style="background-color: #E8E8E8; color: #333; font-weight: 600; border-radius: 8px; padding: 0.75rem; border: none; cursor: pointer;" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn" style="background-color: var(--brand-gold-dark); color: #fff; font-weight: 600; border-radius: 8px; padding: 0.75rem; border: none; cursor: pointer;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="detail-backdrop" id="deleteBackdrop" onclick="closeDeleteModal()"></div>
    <div id="deleteModal" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.9); background: #FFF; border-radius: 12px; z-index: 1055; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 400px; max-width: 90vw; opacity: 0; pointer-events: none; transition: all 0.3s ease; display: none; text-align: center; padding: 2.5rem 2rem;">
        <div style="font-size: 3.5rem; color: #E05A5A; margin-bottom: 0.5rem; line-height: 1;"><i class="bi bi-exclamation-circle"></i></div>
        <h3 style="margin-bottom: 0.75rem; font-size: 1.5rem; color: var(--brand-gold-dark); font-family: 'Cormorant Garamond', serif; font-weight: 700;">Delete Booking</h3>
        <p style="color: #666; margin-bottom: 2rem; font-size: 0.95rem; line-height: 1.5;">Are you sure you want to delete this booking?<br>This action cannot be undone.</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <button class="btn" style="background-color: #E8E8E8; color: #333; font-weight: 600; border-radius: 8px; padding: 0.75rem; border: none; cursor: pointer; transition: background-color 0.2s;" onclick="closeDeleteModal()" onmouseover="this.style.backgroundColor='#D8D8D8'" onmouseout="this.style.backgroundColor='#E8E8E8'">Cancel</button>
            <button class="btn" style="background-color: #FFE5E5; color: #E05A5A; font-weight: 600; border-radius: 8px; padding: 0.75rem; border: none; cursor: pointer; transition: background-color 0.2s;" onclick="confirmDeleteBooking()" onmouseover="this.style.backgroundColor='#FFD0D0'" onmouseout="this.style.backgroundColor='#FFE5E5'">Yes, Delete</button>
        </div>
    </div>

    <!-- Script to toggle offcanvas -->
    <script>
        let currentBookingId = null;
        let currentBookingCode = null;
        let currentBookingData = null;

        function formatRupiah(value) {
            return 'Rp ' + Math.round(Number(value || 0)).toLocaleString('id-ID');
        }

        function formatBookingDate(value) {
            if (!value) return '-';

            const date = new Date(value);
            if (Number.isNaN(date.getTime())) return value;

            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function getGuestName(bookingData) {
            return [bookingData.first_name, bookingData.last_name].filter(Boolean).join(' ').trim() || 'Guest';
        }

        function normalizeWhatsAppNumber(phone) {
            let digits = String(phone || '').replace(/\D/g, '');
            if (!digits) return '';
            if (digits.startsWith('00')) digits = digits.slice(2);
            if (digits.startsWith('0')) digits = `62${digits.slice(1)}`;
            return digits;
        }

        function buildCustomerMessage(bookingData) {
            const guestName = getGuestName(bookingData);
            const checkIn = formatBookingDate(bookingData.check_in);
            const checkOut = formatBookingDate(bookingData.check_out);
            const status = bookingData.status || '-';
            const total = formatRupiah(bookingData.total_price);

            return `Hello ${guestName},

This is Swarna Mandapa Reservations regarding your booking ${bookingData.booking_code}.

Stay dates: ${checkIn} - ${checkOut}
Booking status: ${status}
Total amount: ${total}

Please let us know if you need any assistance with your reservation.

Best regards,
Swarna Mandapa Reservations`;
        }

        function getNightlyBreakdown(bookingData) {
            if (!bookingData.nightly_price_breakdown) return [];
            if (Array.isArray(bookingData.nightly_price_breakdown)) return bookingData.nightly_price_breakdown;
            try {
                return JSON.parse(bookingData.nightly_price_breakdown);
            } catch (e) {
                return [];
            }
        }

        function renderBookingPriceDetail(bookingData, nights) {
            const breakdown = getNightlyBreakdown(bookingData);
            const subtotal = breakdown.length
                ? breakdown.reduce((sum, item) => sum + Number(item.price || 0), 0)
                : Number(bookingData.price_per_night || 0) * nights;
            const discount = Number(bookingData.discount_amount || 0);

            document.getElementById('dp-price-per-night').textContent = formatRupiah(bookingData.price_per_night);
            document.getElementById('dp-nights').textContent = nights;
            document.getElementById('dp-subtotal').textContent = formatRupiah(subtotal);
            document.getElementById('dp-discount').textContent = formatRupiah(discount);
            document.getElementById('dp-total-price').textContent = formatRupiah(bookingData.total_price);

            const box = document.getElementById('dp-season-breakdown');
            if (!breakdown.length) {
                box.style.display = 'none';
                box.innerHTML = '';
                return;
            }

            const grouped = {};
            breakdown.forEach(item => {
                const label = item.label || 'Villa Rate';
                const price = Number(item.price || 0);
                const key = `${label}|${price}`;
                if (!grouped[key]) grouped[key] = { label, price, nights: 0 };
                grouped[key].nights += 1;
            });

            box.innerHTML = '<div style="font-weight:700;color:#7A6953;margin-bottom:.45rem">Nightly Price Breakdown</div>' +
                Object.values(grouped).map(item => (
                    `<div style="display:flex;justify-content:space-between;gap:1rem;margin-top:.25rem">
                        <span>${item.label}: ${item.nights} night${item.nights > 1 ? 's' : ''}</span>
                        <span style="font-weight:600">${formatRupiah(item.price)}</span>
                    </div>`
                )).join('');
            box.style.display = 'block';
        }

        function openDetail(row) {
            try {
                const bookingData = JSON.parse(row.getAttribute('data-booking'));
                currentBookingId = bookingData.id;
                currentBookingCode = bookingData.booking_code;
                currentBookingData = bookingData;
                
                // Format dates
                const checkIn = new Date(bookingData.check_in);
                const checkOut = new Date(bookingData.check_out);
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                
                // Calculate nights
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                
                // Update detail panel
                document.getElementById('dp-booking-code').textContent = bookingData.booking_code;
                document.getElementById('dp-status').textContent = bookingData.status;
                document.getElementById('dp-guest-name').textContent = bookingData.first_name + ' ' + bookingData.last_name;
                document.getElementById('dp-phone').textContent = bookingData.phone;
                document.getElementById('dp-email').textContent = bookingData.email;
                document.getElementById('dp-check-in').textContent = checkIn.toLocaleDateString('en-US', options);
                document.getElementById('dp-check-out').textContent = checkOut.toLocaleDateString('en-US', options);
                document.getElementById('dp-guests').textContent = bookingData.guests;
                const notes = String(bookingData.notes || '').trim();
                document.getElementById('dp-notes').textContent = notes || '-';
                renderBookingPriceDetail(bookingData, nights);
                
                document.getElementById('detailBackdrop').classList.add('show');
                document.getElementById('detailPanel').classList.add('show');
            } catch (e) {
                console.error('Error loading booking details:', e);
            }
        }

        function downloadInvoice() {
            if (!currentBookingCode) return;

            const invoiceUrl = `/booking/invoice/pdf?code=${encodeURIComponent(currentBookingCode)}`;
            window.open(invoiceUrl, '_blank', 'noopener');
        }

        function contactCustomerWhatsApp() {
            if (!currentBookingData) return;

            const phone = normalizeWhatsAppNumber(currentBookingData.phone);
            if (!phone) {
                alert('Customer phone number is not available.');
                return;
            }

            const message = buildCustomerMessage(currentBookingData);
            window.open(`https://wa.me/${phone}?text=${encodeURIComponent(message)}`, '_blank', 'noopener');
        }

        function contactCustomerEmail() {
            if (!currentBookingData) return;

            const email = String(currentBookingData.email || '').trim();
            if (!email) {
                alert('Customer email address is not available.');
                return;
            }

            const subject = `Swarna Mandapa Booking ${currentBookingData.booking_code}`;
            const message = buildCustomerMessage(currentBookingData);
            window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(message)}`;
        }

        function closeDetail() {
            document.getElementById('detailBackdrop').classList.remove('show');
            document.getElementById('detailPanel').classList.remove('show');
        }

        function openEditModal() {
            if (!currentBookingId) return;
            
            // Find the booking data from the table row
            let bookingData = null;
            const rows = document.querySelectorAll('tr[data-booking]');
            for (let row of rows) {
                const data = JSON.parse(row.getAttribute('data-booking'));
                if (data.id === currentBookingId) {
                    bookingData = data;
                    break;
                }
            }
            
            if (!bookingData) return;
            
            // Helper function to format date for input type="date"
            const formatDateForInput = (dateStr) => {
                const date = new Date(dateStr);
                return date.toISOString().split('T')[0];
            };
            
            // Populate form fields
            document.getElementById('editBookingId').value = bookingData.id;
            document.getElementById('editFirstName').value = bookingData.first_name;
            document.getElementById('editLastName').value = bookingData.last_name;
            document.getElementById('editEmail').value = bookingData.email;
            document.getElementById('editPhone').value = bookingData.phone;
            document.getElementById('editCheckIn').value = formatDateForInput(bookingData.check_in);
            document.getElementById('editCheckOut').value = formatDateForInput(bookingData.check_out);
            document.getElementById('editGuests').value = bookingData.guests;
            document.getElementById('editPricePerNight').value = bookingData.price_per_night;
            document.getElementById('editDiscountAmount').value = bookingData.discount_amount;
            document.getElementById('editStatus').value = bookingData.status;
            document.getElementById('editViewNotes').value = String(bookingData.notes || '').trim() || '-';
            
            // Show edit modal and hide detail panel
            document.getElementById('editBackdrop').classList.add('show');
            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editBackdrop').classList.remove('show');
            document.getElementById('editModal').classList.remove('show');
        }

        function deleteBooking() {
            if (!currentBookingId) return;
            document.getElementById('deleteBackdrop').classList.add('show');
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'block';
            // Slight delay for transition to work after display:block
            setTimeout(() => {
                modal.style.opacity = '1';
                modal.style.pointerEvents = 'auto';
                modal.style.transform = 'translate(-50%, -50%) scale(1)';
            }, 10);
        }

        function closeDeleteModal() {
            document.getElementById('deleteBackdrop').classList.remove('show');
            const modal = document.getElementById('deleteModal');
            modal.style.opacity = '0';
            modal.style.pointerEvents = 'none';
            modal.style.transform = 'translate(-50%, -50%) scale(0.9)';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function confirmDeleteBooking() {
            if (!currentBookingId) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/booking-list/${currentBookingId}`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken;
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }

        // Handle Edit Form Submission
        document.getElementById('editBookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const bookingId = formData.get('id');
            
            try {
                const response = await fetch(`/admin/booking-list/${bookingId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        first_name: formData.get('first_name'),
                        last_name: formData.get('last_name'),
                        email: formData.get('email'),
                        phone: formData.get('phone'),
                        check_in: formData.get('check_in'),
                        check_out: formData.get('check_out'),
                        guests: formData.get('guests'),
                        price_per_night: formData.get('price_per_night'),
                        discount_amount: formData.get('discount_amount'),
                        status: formData.get('status')
                    })
                });
                
                if (response.ok) {
                    alert('Booking updated successfully!');
                    location.reload();
                } else {
                    const error = await response.json();
                    alert('Error: ' + (error.message || 'Failed to update booking'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the booking');
            }
        });
    </script>
@endsection
