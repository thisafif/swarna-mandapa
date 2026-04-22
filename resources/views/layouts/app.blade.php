<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Swarna Mandapa — Uluwatu, Bali')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts: Cormorant Garamond + DM Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

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
           NAVBAR
        ========================================= */
        .navbar-swarna {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: .9rem 0;
        }

        .navbar-brand-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.1;
            text-decoration: none;
            color: var(--text-dark);
        }

        .navbar-brand-logo .brand-main {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .navbar-brand-logo .brand-sub {
            font-size: .68rem;
            letter-spacing: .22em;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .nav-link-swarna {
            font-size: 1rem;
            font-family: 'Cormorant Garamond', serif;
            color: var(--gold) !important;
            transition: color .2s;
        }

        .nav-link-swarna:hover { color: var(--gold-light) !important; }

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
           FOOTER
        ========================================= */
        .footer-swarna {
            background: #624F38;
            color: #F8F4EC;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }
        .footer-swarna h5 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.35rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: #fff;
            letter-spacing: .03em;
        }
        .footer-swarna p, .footer-swarna .footer-link {
            font-size: .9rem;
            color: #E2D9CD;
            text-decoration: none;
            transition: color .2s;
        }
        .footer-swarna .footer-link:hover {
            color: var(--gold-light);
        }
        .footer-social-link {
            color: #fff;
            font-size: 1.5rem;
            transition: opacity .2s;
        }
        .footer-social-link:hover {
            opacity: .8;
            color: var(--gold-light);
        }
        .footer-icon-text {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            margin-bottom: 1rem;
            font-size: .9rem;
            color: #E2D9CD;
        }
        .footer-icon-text i {
            font-size: 1.15rem;
            color: #E2D9CD;
            line-height: 1.2;
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

    {{-- ===================== NAVBAR ===================== --}}
    <nav class="navbar navbar-swarna navbar-expand-lg">
        <div class="container">
            {{-- Brand --}}
            <a class="navbar-brand-logo mx-auto mx-lg-0" href="/">
                <img src="{{ asset('images/logo-swarna-mandapa.png') }}" alt="Swarna Mandapa Logo" height="65" style="object-fit: contain;">
            </a>

            {{-- Toggle --}}
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="bi bi-list fs-5 text-gold"></i>
            </button>

            <div class="collapse navbar-collapse text-center mt-3 mt-lg-0" id="mainNav">
                <ul class="navbar-nav mx-auto gap-2 gap-lg-2 mb-3 mb-lg-0">
                    <li class="nav-item"><a class="nav-link nav-link-swarna" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-swarna" href="#">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-swarna" href="#">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-swarna" href="#">Contact Us</a></li>
                </ul>
                <a href="{{ route('booking.form') }}" class="btn btn-gold d-inline-block">Book Now</a>
            </div>
        </div>
    </nav>
    {{-- ===================== END NAVBAR ===================== --}}

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main>
        @yield('content')
    </main>
    {{-- ===================== END MAIN CONTENT ===================== --}}

    {{-- ===================== FOOTER ===================== --}}
    <footer class="footer-swarna">
        <div class="container">
            <div class="row gy-5 gx-3 gx-lg-5">
                <!-- Left Column -->
                <div class="col-lg-6 text-start">
                    <img src="{{ asset('images/logo-swarna-mandapa.png') }}" height="60" alt="Swarna Mandapa Logo" class="mb-3">
                    <p class="mb-5" style="max-width:400px; color: #fff;">A golden sanctuary where tradition and luxury live in perfect harmony.</p>

                    <h5 class="mb-3">Contact Us</h5>
                    <div class="footer-icon-text">
                        <i class="bi bi-geo-alt mt-1"></i>
                        <span>Jl. Nuansa Angkasa III No.7 & 9, Ungasan, Kec. Kuta Sel., Kabupaten Badung, Bali 80361, Indonesia</span>
                    </div>
                    <div class="d-flex flex-wrap gap-4 mt-3">
                        <div class="footer-icon-text mb-0">
                            <i class="bi bi-telephone mt-1"></i>
                            <span>+64 27 297 3575</span>
                        </div>
                        <div class="footer-icon-text mb-0">
                            <i class="bi bi-envelope mt-1"></i>
                            <span>reservations@swarnamandapa.com</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6 text-start text-lg-end d-flex flex-column justify-content-between align-items-start align-items-lg-end mt-4 mt-lg-0">
                    <div class="mb-5 mb-lg-4">
                        <h5>Navigation</h5>
                        <ul class="list-unstyled d-flex flex-column gap-3 gap-lg-2 mb-0">
                            <li><a href="#" class="footer-link">Features</a></li>
                            <li><a href="#" class="footer-link">Gallery</a></li>
                            <li><a href="#" class="footer-link">Reviews</a></li>
                            <li><a href="#" class="footer-link">Contact Us</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="mb-3">Social Media</h5>
                        <div class="d-flex justify-content-start justify-content-lg-end gap-3">
                            <a href="#" class="footer-social-link"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="footer-social-link"><i class="bi bi-tiktok"></i></a>
                            <a href="#" class="footer-social-link"><i class="bi bi-facebook"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,.1); margin: 3rem 0 1.5rem">
            <div class="text-start">
                <span style="font-size: .75rem; color: #bcaaa4 !important">
                    © {{ date('Y') }} Swarna Mandapa — Uluwatu, Bali. All rights reserved.
                </span>
            </div>
        </div>
    </footer>
    {{-- ===================== END FOOTER ===================== --}}

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>