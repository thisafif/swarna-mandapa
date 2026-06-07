{{-- Minimal Invoice (No Header) - For Done Page --}}

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
    .invoice-minimal-wrap {
        background: white;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .invoice-minimal-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }

    .detail-section h3 {
        font-size: 11px;
        text-transform: uppercase;
        color: #999;
        margin: 0 0 0.75rem;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .detail-item {
        margin-bottom: 0.75rem;
    }

    .detail-label {
        font-size: 10px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .detail-value {
        font-size: 12px;
        color: #333;
        font-weight: 500;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 12px;
    }

    .invoice-table thead tr {
        border-top: 2px solid #C9A96E;
        border-bottom: 1px solid #f0f0f0;
    }

    .invoice-table th {
        padding: 0.75rem 0;
        text-align: left;
        font-size: 10px;
        text-transform: uppercase;
        color: #666;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .invoice-table td {
        padding: 0.6rem 0;
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
        padding: 0.75rem 0;
        border-top: 2px solid #C9A96E;
        border-bottom: 2px solid #C9A96E;
        margin: 0.75rem 0;
        font-weight: 600;
        font-size: 12px;
    }

    .total-label {
        color: #333;
    }

    .total-amount {
        color: #C9A96E;
        font-size: 14px;
        font-family: 'Georgia', serif;
    }

    @media print {
        .invoice-minimal-wrap {
            background: white;
            border: none;
            box-shadow: none;
            padding: 0;
        }
    }
</style>

<div class="invoice-minimal-wrap">
    <!-- Booking & Stay Info -->
    <div class="invoice-minimal-details">
        <div class="detail-section">
            <h3>Booking Details</h3>
            <div class="detail-item">
                <div class="detail-label">Code</div>
                <div class="detail-value" style="font-family: monospace;">{{ $bookingCode }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Guest</div>
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
            <h3>Stay Details</h3>
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
                    <span style="color: #999; font-size: 11px;">
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
        <span class="total-label">Total Amount Paid</span>
        <span class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>
</div>
