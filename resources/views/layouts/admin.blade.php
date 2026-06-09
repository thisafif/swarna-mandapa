<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard — Swarna Mandapa</title>
    
    {{-- Bootstrap & Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-gold: #B8924A;
            --brand-gold-dark: #A67C37;
            --brand-bg: #F8F6F2;
            --sidebar-width: 280px;
            --sidebar-bg: #FFFFFF;
            --text-dark: #333333;
            --text-muted: #888888;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--brand-bg);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 1px solid #EBEBEB;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto; /* allow scrolling when content is taller than viewport */
            -webkit-overflow-scrolling: touch;
        }

        .sidebar-brand {
            padding: 2.5rem 2rem 2rem;
            text-align: center;
        }

        .sidebar-brand img {
            width: 160px;
            height: auto;
        }

        .sidebar-brand-divider {
            height: 1px;
            background-color: #EBEBEB;
            margin: 0 2rem;
        }

        .sidebar-nav {
            padding: 2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: auto; /* ensure inner nav can scroll if needed */
        }
        .sidebar-nav form { margin-top: auto; }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .nav-link {
            color: var(--text-dark);
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
            text-decoration: none;
            margin-bottom: 0.25rem;
        }

        .nav-link.active {
            color: var(--brand-gold-dark);
        }

        .nav-link:hover {
            color: var(--brand-gold);
        }

        .nav-link i {
            font-size: 1.1rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            opacity: 0.8;
        }

        .nav-link.active i {
            color: var(--brand-gold-dark);
            opacity: 1;
        }

        .btn-logout {
            margin-top: 0;
            color: #DC3545;
        }
        
        .btn-logout:hover {
            color: #bd2130;
        }

        /* Modal theme overrides to match site */
        .modal-content {
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .modal-header {
            border-bottom: none;
            padding-bottom: 0.5rem;
        }
        .modal-title {
            color: var(--brand-gold-dark);
            font-weight: 700;
            font-family: 'Cormorant Garamond', serif;
        }
        .modal-body {
            color: var(--text-dark);
            font-size: 0.95rem;
        }
        .btn-primary-brand {
            background: var(--brand-gold);
            border-color: var(--brand-gold);
            color: #fff;
            box-shadow: none;
        }
        .btn-primary-brand:hover {
            background: var(--brand-gold-dark);
            border-color: var(--brand-gold-dark);
        }
        .btn-secondary-light {
            background: #F5F5F5;
            border-color: #EAEAEA;
            color: var(--text-dark);
        }

        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            max-width: calc(100% - var(--sidebar-width));
        }

        /* Top Header */
        .top-header {
            background-color: var(--sidebar-bg);
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 3rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .top-header-inner {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        /* Mobile toggle styling */
        .mobile-toggle {
            font-size: 1.4rem;
            color: var(--text-dark);
            cursor: pointer;
            margin-right: auto;
            padding: 0.5rem;
            border-radius: 6px;
            display: none;
        }
        .mobile-toggle:active { background: rgba(0,0,0,0.03); }

        .notification-bell {
            font-size: 1.25rem;
            color: var(--text-dark);
            cursor: pointer;
        }

        .notification-button {
            position: relative;
            border: 0;
            background: transparent;
            padding: 0.35rem;
            line-height: 1;
            color: var(--text-dark);
        }

        .notification-button::after {
            display: none;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #E05A5A;
            color: #FFFFFF;
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transform: translate(35%, -35%);
        }

        .notification-menu {
            width: 360px;
            max-width: calc(100vw - 2rem);
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 14px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .notification-header {
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #F0F0F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .notification-title {
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-dark);
        }

        .notification-count {
            font-size: 0.72rem;
            font-weight: 700;
            color: #E05A5A;
        }

        .notification-list {
            max-height: 360px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            gap: 0.8rem;
            padding: 0.95rem 1.1rem;
            color: var(--text-dark);
            text-decoration: none;
            border-bottom: 1px solid #F5F5F5;
        }

        .notification-item:hover {
            background: #FDFBF7;
            color: var(--text-dark);
        }

        .notification-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            background: #FEF3C7;
            color: #A67C37;
        }

        .notification-text {
            min-width: 0;
            flex: 1;
        }

        .notification-primary {
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .notification-secondary {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.35;
        }

        .notification-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .notification-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-top: 1px solid #F0F0F0;
        }

        .notification-footer a {
            padding: 0.8rem;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--brand-gold-dark);
            text-decoration: none;
        }

        .notification-read-form {
            margin: 0;
        }

        .notification-read-button {
            width: 100%;
            height: 100%;
            border: 0;
            background: transparent;
            padding: 0.8rem;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
        }

        .notification-read-button:not(:disabled) {
            color: var(--brand-gold-dark);
        }

        .notification-read-button:not(:disabled):hover {
            background: #FDFBF7;
        }

        .notification-read-button:disabled {
            cursor: not-allowed;
        }

        .notification-footer a:first-child,
        .notification-read-form {
            border-right: 1px solid #F0F0F0;
        }

        .notification-footer a:hover {
            background: #FDFBF7;
        }

        .header-divider {
            width: 1px;
            height: 24px;
            background-color: #EBEBEB;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .profile-name {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .profile-role {
            font-size: 0.7rem;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        /* Page Content */
        .page-content {
            padding: 3rem;
            flex-grow: 1;
        }

        /* Metrics Grid Layout */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .metric-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .sidebar-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .mobile-toggle { display: inline-flex; margin-right: auto; }
            .sidebar.show { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; max-width: 100%; }
            .top-header { padding: 0 1.25rem; justify-content: space-between; }
            .top-header-inner { gap: 1rem; }
            .profile-info { display: none; }
            .page-content { padding: 1.25rem; }
            .metrics-grid { grid-template-columns: repeat(2, 1fr); }
            .notification-menu {
                width: calc(100vw - 2rem);
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="sidebar-backdrop d-lg-none" onclick="toggleSidebar()"></div>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo-swarna-mandapa.png') }}" alt="Swarna Mandapa Logo">
        </div>
        <div class="sidebar-brand-divider"></div>
        
        <nav class="sidebar-nav">
            <div class="nav-section-title">MAIN MENU</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-fill"></i> Dashboard</a>
            <a href="{{ route('admin.manual_booking') }}" class="nav-link {{ request()->routeIs('admin.manual_booking') ? 'active' : '' }}"><i class="bi bi-calendar-plus"></i> Manual Booking</a>
            <a href="{{ route('admin.booking_list') }}" class="nav-link {{ request()->routeIs('admin.booking_list') ? 'active' : '' }}"><i class="bi bi-card-list"></i> Booking List</a>
            <a href="{{ route('admin.calendar') }}" class="nav-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}"><i class="bi bi-calendar-event"></i> Availability Calendar</a>
            
            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote"></i> 
                <span>Reviews</span>
                @php $pendingCount = \App\Models\GuestReview::where('status','pending')->count(); @endphp
                @if ($pendingCount > 0)
                    <span style="margin-left: auto; background: #E05A5A; color: #fff; font-size: 0.65rem; font-weight: 700; padding: 2px 7px; border-radius: 999px;">
                        {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                    </span>
                @endif
            </a>

            <div class="nav-section-title mt-4">CMS</div>
            <a href="{{ route('admin.home_content.index') }}" class="nav-link {{ request()->routeIs('admin.home_content.*') ? 'active' : '' }}">
                <i class="bi bi-house-heart"></i> Home Content
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Gallery
            </a>
            <a href="{{ route('admin.media_library') }}" class="nav-link {{ request()->routeIs('admin.media_library') ? 'active' : '' }}">
                <i class="bi bi-collection"></i> Media Library
            </a>

            
            <div class="nav-section-title mt-5">CONFIGURATION</div>
            <a href="{{ route('admin.villa_settings') }}" class="nav-link {{ request()->routeIs('admin.villa_settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Villa Settings</a>

            <form id="logoutForm" action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button id="logoutBtn" type="button" class="nav-link btn-logout w-100 text-start border-0 bg-transparent"><i class="bi bi-box-arrow-right"></i> LOG OUT</button>
            </form>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <i class="bi bi-list mobile-toggle d-lg-none" onclick="toggleSidebar()"></i>
            <div class="top-header-inner">
                @php
                    $confirmedNotificationsReadAt = session('admin_confirmed_notifications_read_at');
                    $confirmedNotificationQuery = \App\Models\Booking::where('status', 'CONFIRMED');

                    if ($confirmedNotificationsReadAt) {
                        $confirmedNotificationQuery->where('updated_at', '>', $confirmedNotificationsReadAt);
                    }

                    $confirmedBookingNotifications = (clone $confirmedNotificationQuery)
                        ->orderByDesc('updated_at')
                        ->take(5)
                        ->get();
                    $adminNotificationCount = (clone $confirmedNotificationQuery)->count();
                @endphp

                <div class="dropdown">
                    <button
                        class="notification-button dropdown-toggle"
                        type="button"
                        id="adminNotificationDropdown"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        aria-label="Admin notifications"
                    >
                        <i class="bi bi-bell notification-bell"></i>
                        @if($adminNotificationCount > 0)
                            <span class="notification-badge">{{ $adminNotificationCount > 99 ? '99+' : $adminNotificationCount }}</span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="adminNotificationDropdown">
                        <div class="notification-header">
                            <div class="notification-title">Notifications</div>
                            <div class="notification-count">{{ $adminNotificationCount }} unread confirmed</div>
                        </div>

                        <div class="notification-list">
                            @forelse($confirmedBookingNotifications as $booking)
                                <a class="notification-item" href="{{ route('admin.booking_list', ['status' => 'confirmed']) }}">
                                    <span class="notification-icon"><i class="bi bi-calendar-check"></i></span>
                                    <span class="notification-text">
                                        <span class="notification-primary">Booking confirmed</span>
                                        <span class="notification-secondary">
                                            {{ $booking->booking_code }} - {{ trim($booking->first_name . ' ' . $booking->last_name) ?: 'Guest' }}
                                            @if($booking->updated_at)
                                                <br>{{ $booking->updated_at->diffForHumans() }}
                                            @endif
                                        </span>
                                    </span>
                                </a>
                            @empty
                            @endforelse

                            @if($adminNotificationCount === 0)
                                <div class="notification-empty">No unread confirmed bookings.</div>
                            @endif
                        </div>

                        <div class="notification-footer">
                            <form class="notification-read-form" action="{{ route('admin.notifications.read_all') }}" method="POST">
                                @csrf
                                <button type="submit" class="notification-read-button" {{ $adminNotificationCount === 0 ? 'disabled' : '' }}>Read all</button>
                            </form>
                            <a href="{{ route('admin.booking_list', ['status' => 'confirmed']) }}">Confirmed bookings</a>
                        </div>
                    </div>
                </div>
                <div class="header-divider"></div>
                <div class="user-profile">
                    <div class="profile-info">
                        <span class="profile-name">{{ session('admin_name', 'EGA MUTIARA') }}</span>
                        <span class="profile-role">OWNER</span>
                    </div>
                    <div class="profile-avatar"><i class="bi bi-person"></i></div>
                </div>
            </div>
        </header>

        <div class="page-content">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Logout confirmation modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to log out?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">Cancel</button>
                        <button id="confirmLogoutBtn" type="button" class="btn btn-primary-brand">Yes, Logout</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
                function toggleSidebar() {
                    document.querySelector('.sidebar').classList.toggle('show');
                    const backdrop = document.querySelector('.sidebar-backdrop');
                    if (backdrop.classList.contains('show')) {
                        backdrop.classList.remove('show');
                        setTimeout(() => backdrop.style.display = 'none', 300);
                    } else {
                        backdrop.style.display = 'block';
                        setTimeout(() => backdrop.classList.add('show'), 10);
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                        var logoutBtn = document.getElementById('logoutBtn');
                        var confirmBtn = document.getElementById('confirmLogoutBtn');
                        var logoutForm = document.getElementById('logoutForm');
                        var logoutModalEl = document.getElementById('logoutModal');
                        var logoutModal = new bootstrap.Modal(logoutModalEl, { backdrop: true });

                        if (logoutBtn) {
                                logoutBtn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        logoutModal.show();
                                });
                        }

                        if (confirmBtn) {
                                confirmBtn.addEventListener('click', function() {
                                        logoutForm.submit();
                                });
                        }
                });
        </script>
    @stack('scripts')
</body>
</html>
