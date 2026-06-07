{{-- Shared Invoice Display Component --}}

@php
    $bookingCode   = $booking->booking_code ?? 'SWM-XXXXXX';
    $guestName     = isset($booking->first_name)
                      ? trim($booking->first_name . ' ' . $booking->last_name)
                      : 'Guest';
    $email         = $booking->email ?? '—';
    $phone         = $booking->phone ?? '—';
    $guests        = $booking->guests ?? '—';

    $total         = isset($booking->total_price) ? (int) $booking->total_price : 0;
    $pricePerNight = isset($booking->price_per_night) ? (int) $booking->price_per_night : 0;
    $discount      = isset($booking->discount_amount) ? (int) $booking->discount_amount : 0;
    $promoCode     = $booking->promo_code ?? null;

    $ci     = $booking->check_in  ?? null;
    $co     = $booking->check_out ?? null;
    $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;
    $base   = $pricePerNight * $nights;
    
    $status = $booking->status ?? 'PENDING';
@endphp

<style>
    .invoice-container {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 2rem;
    }

    .invoice-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .invoice-branding h2 {
        font-size: 18px;
        color: #C9A96E;
        margin: 0 0 0.25rem;
        font-weight: 600;
    }

    .invoice-branding p {
        font-size: 12px;
        color: #999;
        margin: 0;
    }

    .invoice-status {
        text-align: right;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .status-pending {
        background: #FEF3C7;
        color: #92400e;
    }

    .status-paid {
        background: #DFF5E8;
        color: #0F5132;
    }

    .invoice-ref {
        font-size: 13px;
        color: #666;
    }

    .invoice-ref strong {
        color: #000;
        display: block;
        margin-bottom: 0.25rem;
    }

    .invoice-details-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .detail-section h3 {
        font-size: 11px;
        text-transform: uppercase;
        color: #999;
        margin: 0 0 1rem;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }

    .detail-value {
        font-size: 13px;
        color: #333;
        font-weight: 500;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
        font-size: 13px;
    }

    .invoice-table thead tr {
        border-top: 2px solid #C9A96E;
        border-bottom: 1px solid #f0f0f0;
    }

    .invoice-table th {
        padding: 1rem 0;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        color: #666;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .invoice-table td {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: 2px solid #C9A96E;
    }

    .text-right {
        text-align: right;
    }

    .amount-normal {
        font-weight: 500;
        color: #333;
    }

    .amount-bold {
        font-weight: 600;
        color: #333;
    }

    .amount-discount {
        color: #16a34a;
        font-weight: 600;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-top: 2px solid #C9A96E;
        border-bottom: 2px solid #C9A96E;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .total-label {
        color: #333;
        font-size: 13px;
    }

    .total-amount {
        color: #C9A96E;
        font-size: 15px;
        font-family: 'Georgia', serif;
    }

    .invoice-footer {
        border-top: 1px solid #f0f0f0;
        padding-top: 1rem;
        font-size: 11px;
        color: #999;
        text-align: center;
    }

    @media print {
        .invoice-container {
            background: white;
            border: none;
            box-shadow: none;
            padding: 20mm;
        }
    }
</style>

<div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header-row">
        <div class="invoice-branding">
            <h2>SWARNA MANDAPA</h2>
            <p>Uluwatu • Bali</p>
        </div>
        <div class="invoice-status">
            <div class="status-badge {{ $status === 'CONFIRMED' ? 'status-paid' : 'status-pending' }}">
                {{ $status === 'CONFIRMED' ? 'PAID' : 'PENDING' }}
            </div>
            <div class="invoice-ref">
                <strong>{{ $bookingCode }}</strong>
                {{ date('d M Y') }}
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="invoice-details-row">
        <div class="detail-section">
            <h3>Guest</h3>
            <div class="detail-item">
                <div class="detail-label">Name</div>
                <div class="detail-value">{{ $guestName }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $email }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Phone</div>
                <div class="detail-value">{{ $phone }}</div>
            </div>
        </div>

        <div class="detail-section">
            <h3>Stay</h3>
            <div class="detail-item">
                <div class="detail-label">Check-in</div>
                <div class="detail-value">{{ $ci ? date('d M Y', strtotime($ci)) : '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Check-out</div>
                <div class="detail-value">{{ $co ? date('d M Y', strtotime($co)) : '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration & Guests</div>
                <div class="detail-value">{{ $nights }} nights • {{ $guests }} guest</div>
            </div>
        </div>
    </div>

    <!-- Invoice Table -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Villa Superior<br>
                    <span style="color: #999; font-size: 12px;">
                        {{ date('d M', strtotime($ci)) }} – {{ date('d M Y', strtotime($co)) }} ({{ $nights }} × Rp {{ number_format($pricePerNight, 0, ',', '.') }})
                    </span>
                </td>
                <td class="text-right amount-bold">Rp {{ number_format($base, 0, ',', '.') }}</td>
            </tr>
            @if($discount > 0)
            <tr>
                <td>Discount {{ $promoCode ? '(' . $promoCode . ')' : '' }}</td>
                <td class="text-right amount-discount">-Rp {{ number_format($discount, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-row">
        <span class="total-label">Total Amount</span>
        <span class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>

    <!-- Footer -->
    <div class="invoice-footer">
        <p><strong>SWARNA MANDAPA</strong></p>
        <p>Jl. Nuansa Angkasa III No.7 & 9, Ungasan, Kec. Kuta Sel., Kabupaten Badung, Bali 80361</p>
        <p>+64 27 297 3575 | reservations@swarnamandapa.com</p>
    </div>
</div>
