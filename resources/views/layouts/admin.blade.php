<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

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
            margin-top: 3rem;
            color: #DC3545;
        }
        
        .btn-logout:hover {
            color: #bd2130;
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
            border-bottom: 3px solid #6DBFF5; /* Blue line matching mockup header border, or maybe it's UI guide? I'll skip the harsh blue line if it was just a selection box. Looking closely it's a blue outline tool from Figma. I'll omit the blue line. */
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

        .notification-bell {
            font-size: 1.25rem;
            color: var(--text-dark);
            cursor: pointer;
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
            cursor: pointer;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background-color: #F0F0F0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--text-dark);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
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

        /* Smooth Dropdown Animation */
        .dropdown-menu.show {
            animation: dropFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transform-origin: top;
        }
        @keyframes dropFadeIn {
            from { opacity: 0; transform: translateY(-10px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Responsive Mobile Layout */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                max-width: 100%;
            }
            .top-header {
                padding: 0 1.5rem;
                justify-content: space-between;
            }
            .mobile-toggle {
                display: block !important;
                font-size: 1.5rem;
                cursor: pointer;
                color: var(--text-dark);
            }
            .header-divider {
                display: none;
            }
            .profile-info {
                display: none;
            }
            .page-content {
                padding: 1.5rem;
                overflow-x: hidden;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
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

            <div class="nav-section-title mt-5">CONFIGURATION</div>
            <a href="{{ route('admin.villa_settings') }}" class="nav-link {{ request()->routeIs('admin.villa_settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Villa Settings</a>

            <a href="{{ route('admin.login') }}" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> LOG OUT</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header border-bottom-0">
            <i class="bi bi-list mobile-toggle d-none" onclick="document.querySelector('.sidebar').classList.toggle('show');"></i>
            <div class="top-header-inner" style="margin-left: auto;">
                <i class="bi bi-bell notification-bell"></i>
                <div class="header-divider"></div>
                <div class="user-profile dropdown">
                    <div class="profile-avatar" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"><i class="bi bi-person"></i></div>
                    <div class="profile-info" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <!-- Dynamic Session Name simulation -->
                        <span class="profile-name" style="text-transform: uppercase;">{{ session('admin_name', 'EGA MUTIARA') }}</span>
                        <span class="profile-role">OWNER</span>
                    </div>
                    <!-- Dropdown Modal Info Admin -->
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="border:none; border-radius:12px; margin-top:12px; padding: 0.5rem 0; min-width: 220px;">
                        <li><h6 class="dropdown-header" style="font-size:0.75rem; color:#888; letter-spacing:0.05em; padding-bottom: 0.5rem;">ADMIN MENU</h6></li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.edit_profile') }}" style="font-size:0.9rem; font-weight:500; color:#444;"><i class="bi bi-person-gear me-2"></i> Edit Profile</a></li>
                        <li><hr class="dropdown-divider" style="border-color:#F0F0F0; margin: 0.5rem 0;"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="{{ route('admin.login') }}" style="font-size:0.9rem; font-weight:600;"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
