@props([
    'title' => 'Modern Admin Dashboard',
    'brandName' => '',
    'brandIcon' => 'fas fa-layer-group'
])

@php

use App\Enums\Role;

@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome-free-7.2.0-web/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css')}}">

    @livewireStyles
    <style>
        :root {
            --sidebar-width: 280px;
            --topbar-height: 70px;

            /* ── Brand palette (from guest.blade.php) ── */
            --accent:          #C8F135;
            --accent-dark:     #a8cc1a;
            --green-brand:     #10b981;
            --green-dark:      #059669;

            /* ── Semantic aliases kept for child-view compatibility ── */
            --primary-color:   #10b981;
            --primary-dark:    #059669;
            --primary-light:   #6ee7b7;
            --secondary-color: #f59e0b;
            --success-color:   #10b981;
            --warning-color:   #f59e0b;
            --danger-color:    #ef4444;

            /* ── Light theme ── */
            --bg-primary:    #F5F5F0;
            --bg-secondary:  #ffffff;
            --bg-tertiary:   #f8fafc;
            --text-primary:  #0D0D0D;
            --text-secondary:#5a5a52;
            --text-muted:    #9a9a8e;
            --border-color:  #E8E8E2;
            --border-light:  #F5F5F0;
            --input-bg:      #ffffff;
            --hover-bg:      #f0f0ea;
            --card-shadow:   0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.12);

            --font-display:  'Syne', sans-serif;
            --font-body:     'DM Sans', sans-serif;
        }

        [data-theme="dark"] {
            --bg-primary:    #0D0D0D;
            --bg-secondary:  #1a1a1a;
            --bg-tertiary:   #222222;
            --text-primary:  #F5F5F0;
            --text-secondary:#a0a090;
            --text-muted:    #606058;
            --border-color:  #2a2a2a;
            --border-light:  #222222;
            --input-bg:      #1a1a1a;
            --hover-bg:      #252525;
            --card-shadow:   0 1px 3px rgba(0,0,0,0.4), 0 1px 2px rgba(0,0,0,0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        /* lime square icon — matches guest navbar */
        .sidebar-brand i {
            width: 32px;
            height: 32px;
            background: var(--accent);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #0D0D0D;
            flex-shrink: 0;
        }

        .sidebar-menu {
            padding: 1.25rem 0;
        }

        .menu-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            font-family: var(--font-display);
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
            margin: 0.15rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .sidebar-menu a:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        /* active: lime accent */
        .sidebar-menu a.active {
            background: var(--accent);
            color: #0D0D0D;
            font-weight: 700;
        }

        .sidebar-menu a i {
            width: 20px;
            margin-right: 10px;
            font-size: 1rem;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--bg-secondary);
            height: var(--topbar-height);
            box-shadow: 0 1px 0 var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            transition: background-color 0.25s ease;
        }

        .topbar .form-control {
            background: var(--input-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
        }

        .topbar .form-control::placeholder { color: var(--text-muted); }

        .topbar .input-group-text {
            background: var(--input-bg);
            border-color: var(--border-color);
            color: var(--text-muted);
        }

        .topbar .user-name  { color: var(--text-primary); font-weight: 600; }
        .topbar .user-role  { color: var(--text-muted); font-size: 0.8rem; }

        /* ── CONTENT ── */
        .content-area { padding: 2rem; }

        /* ── CARDS ── */
        .modern-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: var(--card-shadow);
            transition: all 0.25s ease;
            border: 1px solid var(--border-light);
        }

        .modern-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        [data-theme="dark"] .modern-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.35);
        }

        .stat-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: var(--card-shadow);
            transition: all 0.25s;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color, var(--accent));
        }

        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            transform: translateY(-4px);
        }

        [data-theme="dark"] .stat-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 1rem;
        }

        /* ── USER AVATAR ── */
        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0D0D0D;
            font-weight: 700;
            font-size: 0.875rem;
            font-family: var(--font-display);
        }

        /* ── BADGES ── */
        .badge-modern {
            padding: 0.4rem 0.9rem;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: var(--font-display);
            letter-spacing: 0.02em;
        }

        /* ── BUTTONS ── */
        .btn-modern {
            padding: 0.6rem 1.4rem;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary-modern {
            background: var(--accent);
            color: #0D0D0D;
            border: none;
            font-weight: 700;
            font-family: var(--font-display);
        }

        .btn-primary-modern:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(200,241,53,0.3);
            color: #0D0D0D;
        }

        /* ── THEME TOGGLE ── */
        .theme-toggle {
            background: transparent;
            border: 1.5px solid var(--border-color);
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.4rem 0.55rem;
            border-radius: 6px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            border-color: var(--text-primary);
            color: var(--text-primary);
        }

        .theme-toggle i { font-size: 0.95rem; }

        /* ── TABLES ── */
        .preview-title {
            font-family: var(--font-display);
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-light);
        }

        .table-modern {
            border-collapse: separate;
            border-spacing: 0 0.4rem;
        }

        .table-modern thead th {
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.6rem 1rem;
            font-family: var(--font-display);
        }

        .table-modern tbody tr {
            background: var(--bg-secondary);
            box-shadow: var(--card-shadow);
            border-radius: 6px;
        }

        .table-modern tbody td {
            padding: 0.9rem 1rem;
            border: none;
            vertical-align: middle;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .table-modern tbody tr td:first-child {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .table-modern tbody tr td:last-child {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .table {
            --bs-table-bg: var(--bg-secondary);
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border-color);
            --bs-table-striped-bg: var(--bg-tertiary);
            --bs-table-striped-color: var(--text-primary);
            --bs-table-hover-bg: var(--hover-bg);
            --bs-table-hover-color: var(--text-primary);
        }

        .table > :not(caption) > * > * {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-bottom-color: var(--border-color);
        }

        .table-modern tbody tr         { background: var(--bg-secondary) !important; }
        .table-modern tbody tr:hover   { background: var(--hover-bg) !important; }

        /* ── MODAL ── */
        .modal-backdrop-custom {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content-custom {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            border: 1px solid var(--border-color);
        }

        .modal-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title-custom {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-close-btn {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 1.4rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
        }

        .modal-close-btn:hover { color: var(--danger-color); }

        /* ── FORMS ── */
        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            background: var(--input-bg);
            border: 1.5px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
            padding: 0.7rem 1rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            background: var(--input-bg);
            border-color: var(--green-brand);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.12);
            outline: none;
        }

        .form-control::placeholder { color: var(--text-muted); }

        .input-group-text {
            background: var(--input-bg);
            border: 1.5px solid var(--border-color);
            color: var(--text-muted);
        }

        .input-group .form-control         { background: var(--input-bg); border-color: var(--border-color); }
        .input-group .form-control:focus   { border-color: var(--green-brand); }

        .invalid-feedback { color: var(--danger-color); font-size: 0.8rem; margin-top: 0.25rem; }

        /* ── ACTION BUTTONS ── */
        .action-btn {
            background: transparent;
            border: none;
            padding: 0.45rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover      { background: var(--hover-bg); }
        .action-btn-edit       { color: var(--green-brand); }
        .action-btn-delete     { color: var(--danger-color); }
        .action-btn-view       { color: var(--secondary-color); }

        /* ── ALERTS ── */
        .alert-modern {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: start;
            gap: 12px;
        }

        /* ── PROGRESS ── */
        .progress-modern      { height: 6px; border-radius: 50px; background: var(--border-light); }
        .progress-bar-modern  { border-radius: 50px; }

        /* ── PAGINATION ── */
        .pagination {
            --bs-pagination-bg: var(--bg-secondary);
            --bs-pagination-color: var(--text-primary);
            --bs-pagination-border-color: var(--border-color);
            --bs-pagination-hover-bg: var(--hover-bg);
            --bs-pagination-hover-color: var(--green-brand);
            --bs-pagination-focus-bg: var(--hover-bg);
            --bs-pagination-active-bg: var(--accent);
            --bs-pagination-active-border-color: var(--accent);
            --bs-pagination-active-color: #0D0D0D;
            --bs-pagination-disabled-bg: var(--bg-tertiary);
            --bs-pagination-disabled-color: var(--text-muted);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block !important; }
        }

        .mobile-toggle { display: none; }
        .text-muted { color: var(--text-muted) !important; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <x-sidebar :brand-name="$brandName" :brand-icon="$brandIcon">
        <x-sidebar-section title="Utama">
            <x-sidebar-link href="{{ route('dashboard') }}" icon="fas fa-home" :active="request()->routeIs('dashboard')">Dashboard</x-sidebar-link>

            @if (auth()->user()->role == Role::ADMIN)
                <x-sidebar-link href="{{ route('admin.users') }}" icon="fas fa-users" :active="request()->routeIs('admin.users')">Pengguna</x-sidebar-link>
            @endif
        </x-sidebar-section>

        @if (auth()->user()->role == Role::ADMIN)
            <x-sidebar-section title="Manajemen">
                <x-sidebar-link href="{{ route('admin.produk') }}" icon="fas fa-box" :active="request()->routeIs('admin.produk')">Produk</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.data-penjualan') }}" icon="fas fa-chart-line" :active="request()->routeIs('admin.data-penjualan')">Data Penjualan</x-sidebar-link>
            </x-sidebar-section>
        @endif

        <x-sidebar-section title="Prediksi">
            <x-sidebar-link href="{{ route('admin.prediksi-tahu') }}" icon="fas fa-chart-area" :active="request()->routeIs('admin.prediksi-tahu')">Prediksi WMA</x-sidebar-link>
        </x-sidebar-section>

        <x-sidebar-section title="Pengguna">
            <x-sidebar-link href="{{ route('admin.profile') }}" icon="fas fa-user-circle" :active="request()->routeIs('admin.profile')">Akun</x-sidebar-link>
        </x-sidebar-section>
    </x-sidebar>

    <!-- Main Content -->
    <div class="main-content">
        <x-topbar
            :user-name="Auth::user()?->name ?? 'Guest'"
            user-role="Administrator"
            :notification-count="0"
            :show-logout="true"
        />
        <div class="content-area">
            {{ $slot }}
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function initTheme() {
            const saved       = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved)            document.documentElement.setAttribute('data-theme', saved);
            else if (prefersDark) document.documentElement.setAttribute('data-theme', 'dark');
            updateThemeIcon();
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next    = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const icon   = document.getElementById('theme-icon');
            if (icon) icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }

        initTheme();

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @livewireScripts
</body>
</html>
