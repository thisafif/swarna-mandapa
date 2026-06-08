<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Swarna Mandapa</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 7mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            background: white;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.25;
            height: 196mm;
        }

        .invoice-pdf-wrapper {
            width: 100%;
            height: 196mm;
            padding: 0;
        }

        .invoice-pdf-wrapper .invoice-container {
            width: 100% !important;
            height: 168mm !important;
            border: 1px solid #E3DED4 !important;
            border-radius: 6px !important;
            padding: 9mm 11mm !important;
            position: relative !important;
            page-break-inside: avoid !important;
        }

        .invoice-pdf-wrapper .invoice-header-row {
            display: table !important;
            width: 100% !important;
            margin-bottom: 1.1rem !important;
            padding-bottom: 0.85rem !important;
            border-bottom: 1px solid #EAE5DA !important;
        }

        .invoice-pdf-wrapper .invoice-branding,
        .invoice-pdf-wrapper .invoice-status {
            display: table-cell !important;
            width: 50% !important;
            vertical-align: top !important;
        }

        .invoice-pdf-wrapper .invoice-status {
            text-align: right !important;
        }

        .invoice-pdf-wrapper .invoice-branding h2 {
            font-size: 18px !important;
            margin-bottom: 0.25rem !important;
        }

        .invoice-pdf-wrapper .invoice-branding p,
        .invoice-pdf-wrapper .invoice-ref {
            font-size: 11px !important;
        }

        .invoice-pdf-wrapper .status-badge {
            font-size: 10px !important;
            margin-bottom: 0.25rem !important;
            padding: 0.25rem 0.55rem !important;
        }

        .invoice-pdf-wrapper .invoice-details-row {
            display: table !important;
            width: 100% !important;
            margin-bottom: 1rem !important;
        }

        .invoice-pdf-wrapper .detail-section {
            display: table-cell !important;
            width: 50% !important;
            vertical-align: top !important;
            padding-right: 1rem !important;
        }

        .invoice-pdf-wrapper .detail-section h3 {
            font-size: 9px !important;
            margin-bottom: 0.45rem !important;
            letter-spacing: 0.04em !important;
        }

        .invoice-pdf-wrapper .detail-item {
            margin-bottom: 0.45rem !important;
        }

        .invoice-pdf-wrapper .detail-label {
            font-size: 8px !important;
            margin-bottom: 0.08rem !important;
        }

        .invoice-pdf-wrapper .detail-value {
            font-size: 11px !important;
        }

        .invoice-pdf-wrapper .invoice-table {
            margin: 1rem 0 !important;
            font-size: 11px !important;
        }

        .invoice-pdf-wrapper .invoice-table th {
            font-size: 9px !important;
            padding: 0.45rem 0 !important;
        }

        .invoice-pdf-wrapper .invoice-table td {
            font-size: 11px !important;
            padding: 0.45rem 0 !important;
        }

        .invoice-pdf-wrapper .invoice-table span {
            font-size: 9px !important;
        }

        .invoice-pdf-wrapper .total-row {
            display: table !important;
            width: 100% !important;
            padding: 0.7rem 0 !important;
            margin-bottom: 0.45rem !important;
        }

        .invoice-pdf-wrapper .total-label,
        .invoice-pdf-wrapper .total-amount {
            display: table-cell !important;
            font-size: 12px !important;
        }

        .invoice-pdf-wrapper .total-amount {
            text-align: right !important;
            font-size: 15px !important;
        }

        .invoice-pdf-wrapper .invoice-footer {
            position: absolute !important;
            left: 11mm !important;
            right: 11mm !important;
            bottom: 10mm !important;
            padding-top: 0.55rem !important;
            font-size: 8px !important;
        }

        @media screen {
            body {
                background: #f0f0f0;
                display: flex;
                justify-content: center;
                padding: 2rem;
            }

            .invoice-pdf-wrapper {
                width: min(100%, 960px);
                height: auto;
                background: white;
                padding: 2rem;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
            }
        }

        @media screen and (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .invoice-pdf-wrapper {
                padding: 1rem;
            }
        }

        @media print {
            .invoice-pdf-wrapper {
                width: 100% !important;
                padding: 0 !important;
            }

            .invoice-container {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-pdf-wrapper">
        @include('booking.invoice-shared', ['booking' => $booking])
    </div>
</body>
</html>
