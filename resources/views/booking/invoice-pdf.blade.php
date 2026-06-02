<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Voucher</title>
    <style>
        @page { margin: 0px; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            margin: 0; padding: 0; 
            color: #333; 
            background: #fff;
        }
        .header {
            background-color: #2A2A2A;
            padding: 30px 40px;
            color: #fff;
        }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: middle; }
        .voucher-title {
            font-family: 'Georgia', serif;
            font-size: 28px;
            font-weight: bold;
            color: #C0A47B;
            margin: 0 0 5px 0;
            text-align: right;
        }
        .voucher-id {
            font-size: 14px;
            color: #ccc;
            text-align: right;
        }
        .badge {
            background-color: #ffc107;
            color: #000;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        .body { padding: 40px; }
        .section {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .grid-table { width: 100%; border-collapse: collapse; }
        .grid-table td { padding: 5px 0; vertical-align: top; width: 50%; }
        .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .value {
            font-size: 15px;
            font-weight: bold;
            color: #222;
        }
        .sub-value {
            font-size: 13px;
            color: #666;
            margin-top: 4px;
        }
        
        .four-col td { width: 25%; }

        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .invoice-table th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: #888;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .invoice-table th.right { text-align: right; }
        .invoice-table td {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .invoice-table td.right { text-align: right; font-weight: bold; }
        
        .total-box {
            background-color: #FFF8ED;
            border: 1px solid #F0D9A0;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
        }
        .total-box table { width: 100%; }
        .total-label {
            font-family: 'Georgia', serif;
            font-size: 22px;
            font-weight: bold;
            color: #8B6914;
        }
        .total-amount {
            font-family: 'Georgia', serif;
            font-size: 26px;
            font-weight: bold;
            color: #C9A96E;
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 30px;
        }
    </style>
</head>
<body>

@php
    $bookingCode = $booking->booking_code ?? 'Draft';
    $guestName   = isset($booking->first_name)
                    ? trim($booking->first_name . ' ' . $booking->last_name)
                    : 'Guest';

    $base = isset($booking->total_price) ? (float) $booking->total_price : 0;
    if ($base === 0.0) {
        $ci = session('booking.check_in'); $co = session('booking.check_out');
        $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;
        $base = 5000000 * $nights;
    }
    $tax   = (int) round($base * 0.11);
    $fee   = (int) round($base * 0.10);
    $total = (int) ($base + $tax + $fee);

    $ci     = $booking->check_in  ?? null;
    $co     = $booking->check_out ?? null;
    $nights = ($ci && $co) ? (new DateTime($ci))->diff(new DateTime($co))->days : 0;
    
    // In DOMPDF, local images should ideally be accessed via absolute server path, not URL, 
    // to avoid network fetching loops. But public_path works well.
    $logoPath = public_path('assets/swarna/logo-light.svg');
    // If SVG causes issues, we can fallback to png, but SVG usually works in dompdf 1+
    if(!file_exists($logoPath)) {
        $logoPath = public_path('assets/swarna/logo.png');
    }
    
    // Fallback if public_path() is broken in this environment
    $logoSrc = file_exists($logoPath) ? $logoPath : 'assets/swarna/logo.png';
@endphp

<div class="header">
    <table>
        <tr>
            <td style="width: 50%;">
                <img src="{{ $logoSrc }}" alt="Swarna Mandapa" style="height: 60px;">
            </td>
            <td style="width: 50%; text-align: right;">
                <h1 class="voucher-title">Booking Voucher</h1>
                <div class="voucher-id">ID: {{ $bookingCode }}</div>
                <div><span class="badge">PENDING PAYMENT</span></div>
            </td>
        </tr>
    </table>
</div>

<div class="body">
    <!-- Guest Info -->
    <div class="section">
        <table class="grid-table">
            <tr>
                <td>
                    <div class="label">Billed To</div>
                    <div class="value">{{ $guestName }}</div>
                    <div class="sub-value">{{ $booking->email ?? '—' }}</div>
                </td>
                <td style="text-align: right;">
                    <div class="label">Invoice Date</div>
                    <div class="value">{{ date('d M Y') }}</div>
                    <div class="sub-value">Property: Villa Swarna Mandapa</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Stay Info -->
    <div class="section">
        <table class="grid-table four-col">
            <tr>
                <td>
                    <div class="label">Check-In</div>
                    <div class="value">{{ $ci ? date('d M Y', strtotime($ci)) : '—' }}</div>
                    <div class="sub-value">From 15:00</div>
                </td>
                <td>
                    <div class="label">Check-Out</div>
                    <div class="value">{{ $co ? date('d M Y', strtotime($co)) : '—' }}</div>
                    <div class="sub-value">Until 12:00</div>
                </td>
                <td>
                    <div class="label">Duration</div>
                    <div class="value">{{ $nights }} Nights</div>
                </td>
                <td>
                    <div class="label">Guests</div>
                    <div class="value">{{ $booking->guests ?? '—' }} Guests</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Invoice Details -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="value">Villa Superior</div>
                    @if($nights > 0)
                        <div class="sub-value">{{ date('d M Y', strtotime($ci)) }} — {{ date('d M Y', strtotime($co)) }}</div>
                    @endif
                </td>
                <td class="right">
                    Rp {{ number_format($base, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td><div class="sub-value">Government Tax (11%)</div></td>
                <td class="right"><div class="sub-value">Rp {{ number_format($tax, 0, ',', '.') }}</div></td>
            </tr>
            <tr>
                <td><div class="sub-value">Service Fee (10%)</div></td>
                <td class="right"><div class="sub-value">Rp {{ number_format($fee, 0, ',', '.') }}</div></td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <table>
            <tr>
                <td class="total-label">Total Amount Due</td>
                <td class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Non-refundable. Please complete your payment to secure this booking.<br>
        Swarna Mandapa Resort, Uluwatu, Bali.
    </div>
</div>

</body>
</html>
