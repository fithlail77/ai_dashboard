<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', 'Admin Dashboard - Tabler PT GUM')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            var savedTheme = localStorage.getItem('tabler-theme');
            var theme = savedTheme ? savedTheme : 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
            if (theme === 'dark') {
                document.documentElement.classList.add('theme-dark');
            } else {
                document.documentElement.classList.remove('theme-dark');
            }
        })();
    </script>
    @stack('styles')
    <style>
        .sub-navbar {
            min-height: 2.75rem;
            background-color: var(--tblr-bg-surface);
        }

        .sub-navbar .navbar-nav {
            margin: 0;
            padding: 0;
        }

        .sub-navbar .navbar-nav .nav-link {
            padding: 0.65rem 0.95rem;
            color: var(--tblr-body-color);
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-bottom: none !important;
            transition: all 0.15s ease-in-out;
        }

        .sub-navbar .navbar-nav .nav-item.active>.nav-link,
        .sub-navbar .navbar-nav .nav-link.active {
            color: var(--tblr-primary) !important;
            font-weight: 600;
            border-bottom: none !important;
            border-radius: 0;
            background: transparent !important;
        }

        .sub-navbar .navbar-nav .nav-link:hover {
            color: var(--tblr-primary);
        }

        .sub-navbar .navbar-nav .nav-link .icon {
            width: 1.15rem;
            height: 1.15rem;
        }

        /* Dark mode sub-navbar adjustments */
        [data-bs-theme="dark"] .sub-navbar,
        .theme-dark .sub-navbar {
            background-color: var(--tblr-bg-surface) !important;
            border-color: var(--tblr-border-color) !important;
        }

        [data-bs-theme="dark"] .sub-navbar .nav-link,
        .theme-dark .sub-navbar .nav-link {
            color: var(--tblr-body-color);
        }

        [data-bs-theme="dark"] .dropdown-menu,
        .theme-dark .dropdown-menu {
            background-color: var(--tblr-bg-surface);
            border-color: var(--tblr-border-color);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="page">
        <!-- TOP NAVBAR TIER -->
        <header class="navbar navbar-expand-md d-print-none border-bottom bg-body">
            <div class="container-xl">
                <!-- Mobile Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Brand Logo (PT GUM) -->
                <h1 class="navbar-brand d-none-navbar-horizontal pe-0 pe-md-3 mb-0">
                    <a href="{{ route('dashboard') }}"
                        class="d-inline-flex align-items-center gap-2 text-decoration-none">
                        <img src="{{ asset('build/image/gum.png') }}" alt="PT Grand Utama Mandiri"
                            class="navbar-brand-image" style="height: 36px; width: auto; object-fit: contain;">
                        <span class="badge bg-blue-lt text-uppercase">PT Grand Utama Mandiri</span>
                    </a>
                </h1>

                <!-- Top Right Actions & User Menu -->
                <div class="navbar-nav flex-row order-md-last align-items-center gap-2">
                    <!-- Theme Toggle Button -->
                    <button type="button" id="admin-theme-toggle"
                        class="btn btn-icon btn-ghost-secondary rounded-circle" title="Ganti Mode Gelap / Terang">
                        <svg id="theme-icon-sun" xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-sun" width="20" height="20" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path
                                d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                        </svg>
                        <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-moon d-none" width="20" height="20"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                        </svg>
                    </button>

                    <!-- Language Switcher Badge -->
                    @php $currentLocale = app()->getLocale(); @endphp
                    <a href="{{ route('lang.switch', $currentLocale === 'en' ? 'id' : 'en') }}"
                        class="badge bg-secondary-lt fw-bold px-2 py-1 text-decoration-none d-none d-md-inline-block"
                        title="{{ $currentLocale === 'en' ? 'Ganti ke Bahasa Indonesia' : 'Switch to English (US)' }}">
                        {{ strtoupper($currentLocale) }}
                    </a>

                    <!-- User Profile Dropdown (Pawel Kuna style) -->
                    <div class="nav-item dropdown ms-2">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Buka menu user">
                            <span class="avatar avatar-sm avatar-rounded bg-primary-lt fw-bold text-primary">
                                {{ strtoupper(substr(Auth::user()->name ?? 'PK', 0, 2)) }}
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div class="fw-semibold">{{ Auth::user()->name ?? 'Administrator' }}</div>
                                <div class="mt-1 small text-secondary">
                                    {{ Auth::user()->email ?? 'admin@gum.co.id' }}
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-sm">
                            <div class="dropdown-header">
                                <span class="d-block fw-bold">{{ Auth::user()->name ?? 'Administrator' }}</span>
                                <span class="small text-muted">{{ Auth::user()->email ?? 'admin@gum.co.id' }}</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                {{ __('Profile') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon dropdown-item-icon text-danger" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg>
                                    {{ __('Keluar (Logout)') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- SUB-HEADER NAVBAR (Double Header Baris Kedua sesuai Gambar Pengguna) -->
        <header class="navbar navbar-expand border-bottom bg-body py-0 sub-navbar">
            <div class="container-xl d-flex justify-content-between align-items-center">
                <ul class="navbar-nav flex-row flex-wrap align-items-center">
                    <!-- 1. Beranda -->
                    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">{{ __('Beranda') }}</span>
                        </a>
                    </li>

                    <!-- 2. Dashboard (Dropdown) -->
                    <li class="nav-item dropdown {{ request()->routeIs('dashboard.curah-hujan') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-dashboard" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M12 12l0 9" />
                                    <path d="M12 12l-8 -4.5" />
                                </svg>
                            </span>
                            <span class="nav-link-title">{{ __('Dashboard') }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item {{ request()->routeIs('dashboard.curah-hujan') ? 'active' : '' }}" href="{{ route('dashboard.curah-hujan') }}">{{ __('Curah Hujan') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.aws') ? 'active' : '' }}" href="{{ route('dashboard.aws') }}">{{ __('AWS') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.areal-statement') ? 'active' : '' }}" href="{{ route('dashboard.areal-statement') }}">{{ __('Areal Statement') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.produksi') ? 'active' : '' }}" href="{{ route('dashboard.produksi') }}">{{ __('Produksi') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.analisa-produksi-kebun') ? 'active' : '' }}" href="{{ route('dashboard.analisa-produksi-kebun') }}">{{ __('Analisa Produksi Kebun') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.pemupukan-perawatan') ? 'active' : '' }}" href="{{ route('dashboard.pemupukan-perawatan') }}">{{ __('Pemupukan & Perawatan') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.fleet-cartrack') ? 'active' : '' }}" href="{{ route('dashboard.fleet-cartrack') }}">{{ __('Fleet Cartrack') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.operasional-unit') ? 'active' : '' }}" href="{{ route('dashboard.operasional-unit') }}">{{ __('Operasional Unit') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.infrastruktur-jalan') ? 'active' : '' }}" href="{{ route('dashboard.infrastruktur-jalan') }}">{{ __('Infrastruktur Jalan') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.sampel-daun-lsu') ? 'active' : '' }}" href="{{ route('dashboard.sampel-daun-lsu') }}">{{ __('Sampel Daun (LSU)') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.assessmen-blok') ? 'active' : '' }}" href="{{ route('dashboard.assessmen-blok') }}">{{ __('Assessmen Blok') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.pembibitan') ? 'active' : '' }}" href="{{ route('dashboard.pembibitan') }}">{{ __('Pembibitan') }}</a>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.legal') ? 'active' : '' }}" href="{{ route('dashboard.legal') }}">{{ __('Legal') }}</a>
                        </div>
                    </li>

                    <!-- 3. AI Chat (Dropdown) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-ai-chat" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-messages"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" />
                                    <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" />
                                </svg>
                            </span>
                            <span class="nav-link-title">{{ __('AI Chat') }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#ai-analytic-curah-hujan">{{ __('AI Analytic Curah Hujan') }}</a>
                        </div>
                    </li>

                    <!-- 4. Setting (Dropdown) -->
                    <li class="nav-item dropdown {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-setting" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">{{ __('Setting') }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                {{ __('Pengguna') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </header>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="page-wrapper">
            @yield('content')

            <!-- Tabler Footer -->
            <footer class="footer footer-transparent d-print-none mt-auto py-3 border-top">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><span class="badge bg-green-lt">PostgreSQL:
                                        aidashboard</span></li>
                                <li class="list-inline-item text-muted">Sanctum Token v4</li>
                                <li class="list-inline-item text-muted">Tabler v1.7</li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    &copy; {{ date('Y') }} <a href="#" class="link-secondary fw-semibold">PT
                                        GUM - AI Dashboard Enterprise</a>. Hak Cipta Dilindungi.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
    <script>
        window.applyTablerTheme = function(theme) {
            if (!theme) theme = 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
            if (document.body) {
                document.body.setAttribute('data-bs-theme', theme);
                document.body.setAttribute('data-theme', theme);
            }

            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');

            if (theme === 'dark') {
                document.documentElement.classList.add('theme-dark');
                if (document.body) document.body.classList.add('theme-dark');
                if (sunIcon) sunIcon.classList.remove('d-none');
                if (moonIcon) moonIcon.classList.add('d-none');
            } else {
                document.documentElement.classList.remove('theme-dark');
                if (document.body) document.body.classList.remove('theme-dark');
                if (sunIcon) sunIcon.classList.add('d-none');
                if (moonIcon) moonIcon.classList.remove('d-none');
            }

            try {
                localStorage.setItem('tabler-theme', theme);
            } catch (e) {}
        };

        window.toggleTablerTheme = function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            window.applyTablerTheme(currentTheme === 'dark' ? 'light' : 'dark');
        };

        document.addEventListener('DOMContentLoaded', function() {
            const themeBtn = document.getElementById('admin-theme-toggle');
            const savedTheme = localStorage.getItem('tabler-theme') || 'light';
            window.applyTablerTheme(savedTheme);

            if (themeBtn) {
                themeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.toggleTablerTheme();
                });
            }
        });
    </script>
</body>

</html>
