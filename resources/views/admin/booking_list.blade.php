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
        padding: 0.75rem 1rem 0.75rem 2.8rem;
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
    }
    .detail-backdrop.show {
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
    }
    .detail-panel.show {
        right: 0;
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
        <div class="table-toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="search-input" placeholder="Search by guest name or code">
            </div>
            <div class="filter-box">
                <select class="filter-select">
                    <option value="">Filter by Status</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="pending">Pending</option>
                    <option value="declined">Declined</option>
                </select>
            </div>
        </div>

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
                    @for($i=1; $i<=10; $i++)
                    <tr>
                        <td class="col-no">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="col-code">SWM-2026-0001{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="col-guest">Apipupipupipupu</td>
                        <td class="col-date">Feb 26 - 28, 2026</td>
                        <td class="col-status">CONFIRMED</td>
                        <td class="col-created">Feb 25, 2026 09:00</td>
                        <td class="col-action">
                            <a href="#" class="action-link" onclick="openDetail(); return false;">DETAIL</a>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="table-footer">
            <div class="entries-info">Showing 1-10 of 124 entries</div>
            <div class="pagination">
                <a href="#" class="page-item"><i class="bi bi-chevron-left mt-1" style="font-size:0.7rem"></i></a>
                <a href="#" class="page-item active">1</a>
                <a href="#" class="page-item">2</a>
                <a href="#" class="page-item">3</a>
                <a href="#" class="page-item"><i class="bi bi-chevron-right mt-1" style="font-size:0.7rem"></i></a>
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
                    <div class="dp-status">CONFIRMED</div>
                </div>
                <div class="dp-value" style="font-size:1.2rem; color:var(--brand-gold-dark); font-weight:700;">SWM-2026-000101</div>
            </div>

            <hr style="border-color:#EBEBEB; margin: 1.5rem 0;">

            <div class="dp-section">
                <div class="dp-label">Guest Information</div>
                <div class="dp-value">
                    <strong style="font-size: 1.05rem;">Apipupipupipupu</strong><br>
                    <span style="color:#666; font-size:0.85rem; display:inline-block; margin-top:0.4rem;">
                        <i class="bi bi-whatsapp" style="margin-right:0.3rem"></i> +62 812-3456-7890<br>
                        <i class="bi bi-envelope" style="margin-right:0.3rem"></i> apip@example.com
                    </span>
                </div>
            </div>

            <div class="dp-section">
                <div class="dp-label">Stay Details</div>
                <div class="dp-value-group mb-3">
                    <div>
                        <div style="font-size:0.75rem; color:#888; font-weight: 600; margin-bottom: 0.2rem;">Check-in</div>
                        <div style="font-weight:700; color:#222;">26 Feb 2026</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:#888; font-weight: 600; margin-bottom: 0.2rem;">Check-out</div>
                        <div style="font-weight:700; color:#222;">28 Feb 2026</div>
                    </div>
                </div>
                <div class="p-3" style="background: #FDFBF7; border: 1px solid #EBE4D5; border-radius: 8px;">
                    <div style="font-size:0.75rem; color:#7A6953; font-weight: 600; margin-bottom: 0.2rem;">Accommodation</div>
                    <div style="font-weight:600; color:#222; font-size: 0.9rem;">Entire Villa (4 Bedrooms)</div>
                    <div style="font-size:0.8rem; color:#666; margin-top: 0.4rem;"><i class="bi bi-people-fill text-gold"></i> Up to 8 Guests</div>
                </div>
            </div>

            <hr style="border-color:#EBEBEB; margin: 1.5rem 0;">

            <div class="dp-section">
                <div class="dp-label">Payment Breakdown</div>
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:0.9rem; color:#555;">Payment Method</span>
                    <span style="font-size:0.9rem; font-weight:600;">Credit Card</span>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-3" style="border-top: 1px dashed #DDD;">
                    <span style="font-size:0.9rem; color:#222; font-weight: 600;">Total Amount</span>
                    <span style="font-size:1.1rem; font-weight:700; color:var(--brand-gold-dark);">$ 2,400</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to toggle offcanvas -->
    <script>
        function openDetail() {
            document.getElementById('detailBackdrop').classList.add('show');
            document.getElementById('detailPanel').classList.add('show');
        }
        function closeDetail() {
            document.getElementById('detailBackdrop').classList.remove('show');
            document.getElementById('detailPanel').classList.remove('show');
        }
    </script>
@endsection
