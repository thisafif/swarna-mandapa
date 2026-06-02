@php
    $footerLinks = [
        'Home' => url('/'),
        'Features' => url('/#features'),
        'Gallery' => route('gallery'),
        'Reviews' => route('reviews'),
        'Contact Us' => route('contact-us'),
    ];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Swarna Mandapa - Uluwatu, Bali')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-swarna-mandapa.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=pt-serif:400,400i,700,700i|pt-sans:400,700" rel="stylesheet">
    {{-- Google Fonts: Cormorant Garamond + DM Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =========================================
           CSS VARIABLES — Brand Design Tokens
        ========================================= */
        :root {
            --gold:         #9e7631;
            --gold-light:   #B8924A;
            --gold-pale:    #F0E6D3;
            --cream:        #FAF7F2;
            --cream-dark:   #F2EDE4;
            --text-dark:    #18140B;
            --text-mid:     #4A3F2A;
            --text-muted:   #73634C;
            --border:       #E5D9C5;
            --white:        #FFFFFF;
            --success:      #4A7C59;
            --danger:       #B85450;
            --radius-sm:    6px;
            --radius-md:    12px;
            --radius-lg:    20px;
            --shadow-sm:    0 2px 8px rgba(44,36,22,.06);
            --shadow-md:    0 4px 20px rgba(44,36,22,.10);
            --shadow-lg:    0 8px 40px rgba(44,36,22,.14);
        }

        /* =========================================
           BASE
        ========================================= */
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            color: var(--text-dark);
            font-size: 14px;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6,
        .font-display {
            font-family: 'Cormorant Garamond', serif;
        }

        /* =========================================
           BUTTONS
        ========================================= */
        .btn-gold {
            background: var(--gold);
            color: var(--white);
            border: none;
            border-radius: 50px;
            font-family: 'DM Sans', sans-serif;
            font-size: .82rem;
            font-weight: 500;
            letter-spacing: .06em;
            padding: .6rem 1.6rem;
            transition: background .2s, transform .15s, box-shadow .2s;
        }

        .btn-gold:hover {
            background: var(--gold-light);
            color: var(--white);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(184,146,74,.35);
        }

        .btn-gold-outline {
            background: transparent;
            color: var(--gold);
            border: 1.5px solid var(--gold);
            border-radius: 50px;
            font-size: .82rem;
            font-weight: 500;
            letter-spacing: .06em;
            padding: .6rem 1.6rem;
            transition: all .2s;
        }

        .btn-gold-outline:hover {
            background: var(--gold);
            color: var(--white);
        }

        .btn-gold-lg {
            padding: .85rem 2rem;
            font-size: .9rem;
            font-weight: 600;
            letter-spacing: .08em;
            border-radius: 50px;
        }

        /* =========================================
           CARDS / PANELS
        ========================================= */
        .panel {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.6rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        .panel-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
            padding-bottom: .65rem;
            border-bottom: 1px solid var(--border);
        }

        /* =========================================
           PAGE HEADER
        ========================================= */
        .page-header {
            text-align: center;
            padding: 3rem 0 2rem;
        }

        .page-header .section-label {
            font-size: .7rem;
            letter-spacing: .3em;
            text-transform: uppercase;
            color: var(--gold);
            display: block;
            margin-bottom: .5rem;
        }

        .page-header h1 {
            font-size: clamp(2.4rem, 6vw, 3.2rem);
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: .6rem;
            line-height: 1.2;
        }

        .page-header h1 em {
            font-style: italic;
            color: var(--gold);
        }

        .page-header p {
            color: var(--text-muted);
            font-size: .95rem;
            font-weight: 500;
        }

        /* =========================================
           FORM ELEMENTS
        ========================================= */
        .form-label-sm {
            font-size: .7rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: .3rem;
            font-weight: 500;
        }

        .form-control-swarna,
        .form-select-swarna {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--cream);
            color: var(--text-dark);
            font-size: .88rem;
            padding: .6rem .9rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control-swarna:focus,
        .form-select-swarna:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(184,146,74,.15);
            background: var(--white);
            outline: none;
        }

        /* =========================================
           BADGE / STATUS
        ========================================= */
        .badge-status {
            font-size: .68rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .3em .8em;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-requested  { background: #FFF3CD; color: #856404; }
        .badge-confirmed  { background: #D1E7DD; color: #0A3622; }
        .badge-paid       { background: #D1E7DD; color: #0A3622; }
        .badge-pending    { background: #FFF3CD; color: #856404; }
        .badge-cancelled  { background: #F8D7DA; color: #842029; }

        /* =========================================
           PRICE SUMMARY SIDEBAR
        ========================================= */
        .price-summary-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 1.5rem;
        }

        .price-summary-header {
            background: var(--gold);
            padding: 1.1rem 1.4rem;
        }

        .price-summary-header h6 {
            color: var(--white);
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 .1rem;
        }

        .price-summary-header small {
            color: rgba(255,255,255,.75);
            font-size: .75rem;
        }

        .price-summary-body { padding: 1.2rem 1.4rem; }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .45rem 0;
            font-size: .86rem;
            color: var(--text-mid);
        }

        .price-row.total {
            border-top: 1px solid var(--border);
            margin-top: .4rem;
            padding-top: .8rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: .95rem;
        }

        .price-total-amount {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gold);
        }

        /* =========================================
           DIVIDER ORNAMENT
        ========================================= */
        .ornament-divider {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: var(--gold-light);
            margin: 1.2rem 0;
        }

        .ornament-divider::before,
        .ornament-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* =========================================
           UTILITIES
        ========================================= */
        .text-gold     { color: var(--gold) !important; }
        .text-muted-sm { color: var(--text-muted); font-size: .8rem; }
        .bg-cream      { background: var(--cream) !important; }
        .border-gold   { border-color: var(--gold) !important; }
        .required-star { color: var(--danger); }

        @media (max-width: 768px) {
            .page-header { padding: 2rem 0 1.5rem; }
            .panel { padding: 1.2rem; }
            .price-summary-card { position: static; margin-top: 1.5rem; }
            .container { padding-left: 1.25rem; padding-right: 1.25rem; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <x-site-header />

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="overflow-hidden pt-[80px] sm:pt-[96px]">
        @yield('content')
    </main>
    {{-- ===================== END MAIN CONTENT ===================== --}}

    <x-site-footer :links="$footerLinks" />

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
