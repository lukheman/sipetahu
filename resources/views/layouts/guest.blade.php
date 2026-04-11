@props([
    'title' => 'SITAHU - Sistem Prediksi Penjualan Tahu',
    'description' => 'Sistem Cerdas untuk Membantu Pabrik Tahu Tempe Sumber Urip Menganalisis dan Memprediksi Penjualan Menggunakan Metode Weighted Moving Average.',
    'type' => 'guest',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>

    <!-- Fonts: Syne (display) + DM Sans (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome-free-7.2.0-web/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css')}}">

    <style>
        /* ===== DESIGN SYSTEM ===== */
        :root {
            --accent:        #C8F135;
            --accent-dark:   #a8cc1a;
            --ink:           #0D0D0D;
            --ink-soft:      #1a1a1a;
            --chalk:         #F5F5F0;
            --mist:          #E8E8E2;
            --text-mid:      #5a5a52;
            --text-faint:    #9a9a8e;
            --green-brand:   #10b981;
            --green-dark:    #059669;

            --font-display:  'Syne', sans-serif;
            --font-body:     'DM Sans', sans-serif;

            --radius-sm:  6px;
            --radius-md:  12px;
            --radius-lg:  20px;
            --radius-xl:  32px;

            --nav-height: 68px;
            --ease:       cubic-bezier(0.4, 0, 0.2, 1);
            --duration:   0.25s;

            /* legacy aliases so any existing child views still work */
            --primary-color:   #10b981;
            --primary-dark:    #059669;
            --primary-light:   #6ee7b7;
            --secondary-color: #f59e0b;
            --success-color:   #10b981;
            --warning-color:   #f59e0b;
            --danger-color:    #ef4444;
            --text-primary:    #0D0D0D;
            --text-secondary:  #5a5a52;
            --text-muted:      #9a9a8e;
            --border-color:    #E8E8E2;
            --bg-light:        #F5F5F0;
            --bg-white:        #ffffff;
        }

        [data-theme="dark"] {
            --chalk:        #0D0D0D;
            --mist:         #1a1a1a;
            --ink:          #F5F5F0;
            --ink-soft:     #e0e0d8;
            --text-mid:     #a0a090;
            --text-faint:   #606058;

            --bg-light:     #0D0D0D;
            --bg-white:     #1a1a1a;
            --text-primary: #F5F5F0;
            --text-secondary:#a0a090;
            --border-color: #2a2a2a;
        }

        *, *::before, *::after {
            margin: 0; padding: 0;
            box-sizing: border-box;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--chalk);
            color: var(--ink);
            line-height: 1.6;
            overflow-x: hidden;
            transition: background var(--duration) var(--ease),
                        color var(--duration) var(--ease);
        }

        /* ===== NAVBAR ===== */
        .site-navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            height: var(--nav-height);
            background: rgba(245, 245, 240, 0.94);
            backdrop-filter: blur(24px) saturate(180%);
            border-bottom: 1px solid var(--mist);
            transition: background var(--duration) var(--ease),
                        box-shadow var(--duration) var(--ease);
        }

        [data-theme="dark"] .site-navbar {
            background: rgba(13, 13, 13, 0.94);
            border-bottom-color: #222;
        }

        .site-navbar.scrolled {
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }

        .site-navbar-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        /* Brand */
        .site-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-icon-box {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon-box i {
            font-size: 0.9rem;
            color: var(--ink);
        }

        .brand-wordmark {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        /* Nav links */
        .site-nav {
            display: flex;
            align-items: center;
            gap: 0.125rem;
            list-style: none;
            margin: 0; padding: 0;
        }

        .site-nav-link {
            display: block;
            color: var(--text-mid);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.45rem 0.85rem;
            border-radius: var(--radius-sm);
            transition: color var(--duration) var(--ease),
                        background var(--duration) var(--ease);
            white-space: nowrap;
        }

        .site-nav-link:hover {
            color: var(--ink);
            background: var(--mist);
        }

        /* Action buttons */
        .site-navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            flex-shrink: 0;
        }

        /* Theme toggle */
        .theme-toggle {
            background: transparent;
            border: 1.5px solid var(--mist);
            color: var(--text-mid);
            cursor: pointer;
            padding: 0.4rem 0.55rem;
            border-radius: var(--radius-sm);
            transition: all var(--duration) var(--ease);
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .theme-toggle:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        .theme-toggle i { font-size: 0.9rem; }

        /* "Login / Masuk" ghost button */
        .btn-nav-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.48rem 1.05rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            color: var(--ink);
            background: transparent;
            border: 1.5px solid var(--mist);
            transition: all var(--duration) var(--ease);
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-nav-outline:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        /* "Mulai Prediksi" CTA button */
        .btn-nav-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.52rem 1.2rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.875rem;
            text-decoration: none;
            color: var(--ink) !important;
            background: var(--accent);
            border: none;
            transition: all var(--duration) var(--ease);
            cursor: pointer;
            font-family: var(--font-display);
            letter-spacing: 0.01em;
            white-space: nowrap;
        }

        .btn-nav-primary:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(200,241,53,0.3);
        }

        /* Mobile hamburger */
        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: 1.5px solid var(--mist);
            color: var(--ink);
            font-size: 1rem;
            cursor: pointer;
            padding: 0.4rem 0.6rem;
            border-radius: var(--radius-sm);
            align-items: center;
            justify-content: center;
            transition: border-color var(--duration) var(--ease);
        }

        .mobile-menu-btn:hover { border-color: var(--ink); }

        @media (max-width: 900px) {
            .site-nav { display: none; }
            .mobile-menu-btn { display: flex; }
        }

        @media (max-width: 640px) {
            .btn-nav-outline { display: none; }
        }

        /* ===== SHARED UTILITIES ===== */
        .container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section { padding: 6rem 0; }

        /* Generic buttons (used by child views) */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all var(--duration) var(--ease);
            cursor: pointer;
            border: none;
            font-family: var(--font-body);
        }

        .btn-primary {
            background: var(--accent);
            color: var(--ink);
            font-weight: 700;
            font-family: var(--font-display);
        }

        .btn-primary:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(200,241,53,0.25);
            color: var(--ink);
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--mist);
            color: var(--ink);
        }

        .btn-outline:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        .btn-lg {
            padding: 0.9rem 2rem;
            font-size: 1rem;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--ink);
            color: var(--chalk);
            padding: 4rem 0 0;
        }

        .footer-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-top {
            display: flex;
            justify-content: space-between;
            gap: 3rem;
            flex-wrap: wrap;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .footer-brand-area { max-width: 300px; }

        .footer-brand-area .brand-wordmark { color: var(--chalk); }

        .footer-brand-area p {
            color: rgba(255,255,255,0.45);
            margin-top: 1rem;
            font-size: 0.875rem;
            line-height: 1.75;
        }

        .footer-pill {
            display: inline-block;
            background: var(--accent);
            color: var(--ink);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.22rem 0.7rem;
            border-radius: 20px;
            margin-top: 1rem;
            font-family: var(--font-display);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .footer-links {
            display: flex;
            gap: 4rem;
            flex-wrap: wrap;
        }

        .footer-column h4 {
            font-family: var(--font-display);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 1.25rem;
        }

        .footer-column ul { list-style: none; }

        .footer-column li { margin-bottom: 0.75rem; }

        .footer-column a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color var(--duration) var(--ease);
        }

        .footer-column a:hover { color: var(--accent); }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.75rem 0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-bottom p {
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .footer-top { flex-direction: column; }
            .footer-links { flex-direction: column; gap: 2rem; }
            .footer-bottom { flex-direction: column; text-align: center; }
        }

        /* ===== AUTH SECTION ===== */
        .auth-section {
            min-height: 100vh;
            padding-top: var(--nav-height);
            background: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: calc(var(--nav-height) + 3rem) 2rem 3rem;
            position: relative;
            overflow: hidden;
        }

        .auth-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 15% 60%, rgba(200,241,53,0.07) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 20%, rgba(16,185,129,0.07) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Animated bg shapes (kept for compat, now subtler) */
        .bg-shapes {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(200,241,53,0.04);
            animation: floatShape 18s infinite ease-in-out;
        }

        .bg-shapes .shape:nth-child(1) {
            width: 500px; height: 500px;
            top: -150px; left: -150px;
            animation-delay: 0s;
        }

        .bg-shapes .shape:nth-child(2) {
            width: 350px; height: 350px;
            bottom: -80px; right: -80px;
            animation-delay: -6s;
        }

        .bg-shapes .shape:nth-child(3) {
            width: 220px; height: 220px;
            top: 45%; left: 45%;
            animation-delay: -12s;
        }

        @keyframes floatShape {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33%       { transform: translateY(-28px) rotate(8deg); }
            66%       { transform: translateY(18px) rotate(-5deg); }
        }

        /* Auth card */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: var(--chalk);
            border-radius: var(--radius-xl);
            box-shadow: 0 40px 80px rgba(0,0,0,0.45), 0 0 0 1px rgba(255,255,255,0.06);
            padding: 3rem;
            animation: slideUp 0.5s var(--ease) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo .icon-wrapper {
            width: 68px; height: 68px;
            background: var(--accent);
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .brand-logo .icon-wrapper i {
            font-size: 1.85rem;
            color: var(--ink);
        }

        .brand-logo h1 {
            font-family: var(--font-display);
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--ink);
            margin-bottom: 0.35rem;
        }

        .brand-logo p {
            color: var(--text-mid);
            font-size: 0.875rem;
        }

        /* Form elements in auth card */
        .form-floating { margin-bottom: 1.25rem; }

        .form-floating .form-control {
            height: 58px;
            border: 1.5px solid var(--mist);
            border-radius: var(--radius-sm);
            padding: 1rem 1rem 1rem 2.75rem;
            font-size: 0.95rem;
            background: var(--chalk);
            color: var(--ink);
            transition: border-color var(--duration) var(--ease),
                        box-shadow var(--duration) var(--ease);
        }

        .form-floating .form-control:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(13,13,13,0.08);
            background: #fff;
            outline: none;
        }

        .form-floating label {
            padding: 1rem 1rem 1rem 2.75rem;
            color: var(--text-faint);
            font-size: 0.875rem;
        }

        .form-floating .input-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-faint);
            font-size: 0.95rem;
            z-index: 5;
            transition: color var(--duration) var(--ease);
        }

        .form-floating:focus-within .input-icon {
            color: var(--ink);
        }

        .password-toggle {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-faint);
            cursor: pointer;
            z-index: 5;
            padding: 0.25rem;
            font-size: 0.9rem;
            transition: color var(--duration) var(--ease);
        }

        .password-toggle:hover { color: var(--ink); }

        .form-check-input:checked {
            background-color: var(--green-brand);
            border-color: var(--green-brand);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
            border-color: var(--green-brand);
        }

        .form-check-label {
            color: var(--text-mid);
            cursor: pointer;
            font-size: 0.875rem;
        }

        .forgot-password {
            color: var(--green-brand);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: color var(--duration) var(--ease);
        }

        .forgot-password:hover {
            color: var(--green-dark);
            text-decoration: underline;
        }

        /* Login submit button */
        .btn-login {
            width: 100%;
            height: 52px;
            background: var(--ink);
            border: none;
            border-radius: var(--radius-sm);
            color: var(--chalk);
            font-size: 1rem;
            font-weight: 700;
            font-family: var(--font-display);
            letter-spacing: 0.01em;
            transition: all var(--duration) var(--ease);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login i {
            margin-left: 0.5rem;
            transition: transform var(--duration) var(--ease);
        }

        .btn-login:hover i { transform: translateX(4px); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--text-faint);
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--mist);
        }

        .divider span { padding: 0 1rem; }

        /* Social login */
        .social-login { display: flex; gap: 0.75rem; }

        .btn-social {
            flex: 1;
            height: 46px;
            border: 1.5px solid var(--mist);
            border-radius: var(--radius-sm);
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--ink);
            transition: all var(--duration) var(--ease);
            text-decoration: none;
            cursor: pointer;
        }

        .btn-social:hover {
            border-color: var(--ink);
            background: var(--mist);
            transform: translateY(-1px);
        }

        .btn-social i { font-size: 1.1rem; }
        .btn-social.google i  { color: #ea4335; }
        .btn-social.github i  { color: #333; }

        /* Sign-up link */
        .signup-link {
            text-align: center;
            margin-top: 1.75rem;
            color: var(--text-mid);
            font-size: 0.875rem;
        }

        .signup-link a {
            color: var(--green-brand);
            font-weight: 600;
            text-decoration: none;
            transition: color var(--duration) var(--ease);
        }

        .signup-link a:hover {
            color: var(--green-dark);
            text-decoration: underline;
        }

        /* Auth responsive */
        @media (max-width: 576px) {
            .login-card { padding: 2rem 1.5rem; }
            .brand-logo .icon-wrapper { width: 58px; height: 58px; }
            .brand-logo .icon-wrapper i { font-size: 1.5rem; }
            .brand-logo h1 { font-size: 1.4rem; }
            .social-login { flex-direction: column; }
        }

        /* Autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px var(--chalk) inset;
            -webkit-text-fill-color: var(--ink);
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
    {{ $styles ?? '' }}
</head>

<body>
    {{-- ===== NAVBAR ===== --}}
    <nav class="site-navbar" id="site-navbar">
        <div class="site-navbar-container">

            {{-- Brand --}}
            <a href="/" class="site-brand">
                <div class="brand-icon-box">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="brand-wordmark">SITAHU</span>
            </a>

            {{-- Nav links --}}
            <ul class="site-nav">
                <li><a href="/#beranda" class="site-nav-link">Beranda</a></li>
                <li><a href="/#tentang" class="site-nav-link">Tentang</a></li>
                <li><a href="/#fitur"   class="site-nav-link">Fitur</a></li>
                <li><a href="/#metode"  class="site-nav-link">Metode</a></li>
                <li><a href="/#kontak"  class="site-nav-link">Kontak</a></li>
            </ul>

            {{-- Actions --}}
            <div class="site-navbar-actions">
                <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
                <a href="{{ route('login') }}" class="btn-nav-outline">Masuk</a>
                <a href="/#prediksi" class="btn-nav-primary">
                    Mulai Prediksi
                    <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i>
                </a>
                <button class="mobile-menu-btn" aria-label="Open menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    {{-- ===== CONTENT ===== --}}
    @if($type === 'auth')
        <section class="auth-section">
            <div class="bg-shapes">
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
            </div>
            {{ $slot }}
        </section>

    @else
        <main>
            {{ $slot }}
        </main>

        <footer class="footer">
            <div class="footer-inner">
                <div class="footer-top">
                    {{-- Brand blurb --}}
                    <div class="footer-brand-area">
                        <a href="/" class="site-brand" style="text-decoration:none;">
                            <div class="brand-icon-box">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="brand-wordmark">SITAHU</span>
                        </a>
                        <p>Sistem cerdas prediksi penjualan produk tahu menggunakan metode Weighted Moving Average (WMA) untuk UMKM Indonesia.</p>
                        <span class="footer-pill">WMA Powered</span>
                    </div>

                    {{-- Link columns --}}
                    <div class="footer-links">
                        <div class="footer-column">
                            <h4>Menu</h4>
                            <ul>
                                <li><a href="#beranda">Beranda</a></li>
                                <li><a href="#tentang">Tentang</a></li>
                                <li><a href="#fitur">Fitur</a></li>
                                <li><a href="#metode">Metode</a></li>
                            </ul>
                        </div>
                        <div class="footer-column">
                            <h4>Studi Kasus</h4>
                            <ul>
                                <li><a href="#">Pabrik Tahu Tempe Sumber Urip</a></li>
                                <li><a href="{{ route('login') }}">Akses Sistem</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} SITAHU. All rights reserved.</p>
                    <p>Dibuat untuk UMKM Indonesia 🇮🇩</p>
                </div>
            </div>
        </footer>
    @endif

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        /* ===== THEME ===== */
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

        /* ===== NAVBAR SCROLL EFFECT ===== */
        const navbar = document.getElementById('site-navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 16) navbar.classList.add('scrolled');
            else                      navbar.classList.remove('scrolled');
        }, { passive: true });

        /* ===== SMOOTH SCROLL ===== */
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const href   = a.getAttribute('href');
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
    {{ $scripts ?? '' }}
</body>

</html>
