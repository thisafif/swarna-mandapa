<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice — Swarna Mandapa</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 0.6cm 0.8cm;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            width: 100%;
            background: white;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        /* ── Compact overrides agar muat 1 halaman A5 landscape ── */
        .invoice-container {
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }
        .invoice-header-row { margin-bottom: 0.6rem !important; padding-bottom: 0.5rem !important; }
        .invoice-branding h2 { font-size: 13px !important; }
        .invoice-branding p { font-size: 9px !important; }
        .status-badge { font-size: 9px !important; padding: 0.2rem 0.45rem !important; }
        .invoice-ref { font-size: 10px !important; }
        .invoice-details-row { gap: 1rem !important; margin-bottom: 0.6rem !important; }
        .detail-section h3 { font-size: 8px !important; margin-bottom: 0.4rem !important; }
        .detail-item { margin-bottom: 0.3rem !important; }
        .detail-label { font-size: 8px !important; margin-bottom: 0.15rem !important; }
        .detail-value { font-size: 10px !important; }
        .invoice-table { margin: 0.5rem 0 !important; font-size: 10px !important; }
        .invoice-table th { font-size: 8px !important; padding: 0.4rem 0 !important; }
        .invoice-table td { padding: 0.35rem 0 !important; font-size: 10px !important; }
        .total-row { padding: 0.4rem 0 !important; margin-bottom: 0.4rem !important; }
        .total-label { font-size: 10px !important; }
        .total-amount { font-size: 12px !important; }
        .invoice-footer { padding-top: 0.4rem !important; font-size: 8px !important; }

        @media screen {
            body { background: #f0f0f0; display: flex; justify-content: center; padding: 2rem; }
            .invoice-pdf-wrapper {
                width: 210mm; background: white;
                padding: 0.8cm;
                box-shadow: 0 2px 12px rgba(0,0,0,0.15);
            }
        }
    </style>
</head>
<body>
    <div class="invoice-pdf-wrapper">
        @include('booking.invoice-shared', ['booking' => $booking])
    </div>
    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 300);
        });
    </script>
</body>
</html>