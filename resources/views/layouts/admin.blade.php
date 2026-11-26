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

        /* Kotak Shortcut Grey Sidebar */
        .shortcut-sidebar {
            width: 220px;
            min-width: 220px;
            max-width: 220px;
            background-color: #f1f5f9;
            border-right: 1px solid var(--tblr-border-color);
            display: flex;
            flex-direction: column;
            transition: width 0.2s ease, min-width 0.2s ease, max-width 0.2s ease;
            user-select: none;
            flex-shrink: 0;
        }

        [data-bs-theme="dark"] .shortcut-sidebar,
        .theme-dark .shortcut-sidebar {
            background-color: #182433 !important;
            border-color: var(--tblr-border-color) !important;
        }

        .shortcut-header {
            padding: 0.65rem 0.85rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(0, 0, 0, 0.02);
            min-height: 42px;
        }

        [data-bs-theme="dark"] .shortcut-header,
        .theme-dark .shortcut-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background-color: rgba(255, 255, 255, 0.02);
        }

        .shortcut-header-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--tblr-secondary);
        }

        .shortcut-items-container {
            padding: 0.5rem;
            overflow-y: auto;
            flex-grow: 1;
        }

        .shortcut-item {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 0.3rem;
            border-radius: 6px;
            transition: background-color 0.15s ease;
        }

        .shortcut-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="dark"] .shortcut-item:hover,
        .theme-dark .shortcut-item:hover {
            background-color: rgba(255, 255, 255, 0.06);
        }

        .shortcut-item.active {
            background-color: var(--tblr-primary-lt, rgba(32, 107, 196, 0.12));
        }

        .shortcut-item.active .shortcut-item-link {
            color: var(--tblr-primary) !important;
            font-weight: 600;
        }

        .shortcut-item-link {
            flex-grow: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.55rem;
            color: var(--tblr-body-color);
            text-decoration: none;
            font-size: 0.8125rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            min-width: 0;
        }

        .shortcut-item-link .icon {
            width: 1.15rem;
            height: 1.15rem;
            flex-shrink: 0;
            color: var(--tblr-secondary);
        }

        .shortcut-item.active .shortcut-item-link .icon {
            color: var(--tblr-primary);
        }

        .shortcut-item-remove {
            opacity: 0;
            border: none;
            background: transparent;
            color: var(--tblr-danger);
            padding: 0.2rem 0.35rem;
            margin-right: 0.25rem;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.15s ease, background-color 0.15s ease;
            flex-shrink: 0;
        }

        .shortcut-item:hover .shortcut-item-remove {
            opacity: 0.75;
        }

        .shortcut-item-remove:hover {
            opacity: 1 !important;
            background-color: rgba(214, 57, 57, 0.1);
        }

        /* Collapsed Sidebar */
        .shortcut-sidebar.collapsed {
            width: 52px;
            min-width: 52px;
            max-width: 52px;
        }

        .shortcut-sidebar.collapsed .shortcut-header-title,
        .shortcut-sidebar.collapsed .shortcut-item-text,
        .shortcut-sidebar.collapsed .shortcut-item-remove,
        .shortcut-sidebar.collapsed #btn-open-shortcut-modal {
            display: none !important;
        }

        .shortcut-sidebar.collapsed .shortcut-header {
            justify-content: center;
            padding: 0.65rem 0.25rem;
        }

        .shortcut-sidebar.collapsed .shortcut-item-link {
            justify-content: center;
            padding: 0.5rem 0;
        }

        .shortcut-sidebar.collapsed .shortcut-item-link .icon {
            margin: 0;
        }

        .shortcut-empty-state {
            padding: 1.5rem 0.75rem;
            text-align: center;
            color: var(--tblr-secondary);
            font-size: 0.75rem;
        }

        @media (max-width: 767.98px) {
            .shortcut-sidebar {
                position: fixed;
                top: 110px;
                bottom: 0;
                left: -220px;
                z-index: 1040;
                box-shadow: var(--tblr-box-shadow);
                transition: left 0.3s ease-in-out;
            }
            .shortcut-sidebar.show-mobile {
                left: 0;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="page">
        <!-- TOP NAVBAR TIER -->
        <header class="navbar navbar-expand-md d-print-none border-bottom bg-body">
            <div class="container-fluid px-3 px-md-4">
                <!-- Mobile Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Brand Logo (PT GUM) -->
                <h1 class="navbar-brand d-none-navbar-horizontal pe-0 pe-md-3 mb-0">
                    <a href="{{ route('dashboard') }}"
                        class="d-inline-flex align-items-center gap-2 text-decoration-none">
                        <img src="{{ asset('images/gum.png') }}" alt=""
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

                    <!-- Language Switcher Dropdown (ID / EN / KO) -->
                    @php $currentLocale = app()->getLocale(); @endphp
                    <div class="nav-item dropdown">
                        <a href="#" class="badge bg-secondary-lt fw-bold px-2 py-1 text-decoration-none dropdown-toggle d-inline-flex align-items-center gap-1"
                            data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Bahasa / Language / 언어 선택">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M3.6 9h16.8" />
                                <path d="M3.6 15h16.8" />
                                <path d="M11.5 3a17 17 0 0 0 0 18" />
                                <path d="M12.5 3a17 17 0 0 1 0 18" />
                            </svg>
                            <span>{{ strtoupper($currentLocale) }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-sm">
                            <a href="{{ route('lang.switch', 'id') }}" class="dropdown-item d-flex align-items-center justify-content-between {{ $currentLocale === 'id' ? 'active' : '' }}">
                                <span>🇮🇩 Bahasa Indonesia</span>
                                <span class="badge bg-secondary-lt ms-2">ID</span>
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item d-flex align-items-center justify-content-between {{ $currentLocale === 'en' ? 'active' : '' }}">
                                <span>🇺🇸 English</span>
                                <span class="badge bg-secondary-lt ms-2">EN</span>
                            </a>
                            <a href="{{ route('lang.switch', 'ko') }}" class="dropdown-item d-flex align-items-center justify-content-between {{ $currentLocale === 'ko' ? 'active' : '' }}">
                                <span>🇰🇷 한국어 (Korean)</span>
                                <span class="badge bg-secondary-lt ms-2">KO</span>
                            </a>
                        </div>
                    </div>

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
            <div class="container-fluid px-3 px-md-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary d-md-none me-2" id="btn-mobile-shortcut" title="Buka Shortcut">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" /></svg>
                    </button>
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
            </div>
        </header>

        <!-- MAIN LAYOUT WRAPPER (SHORTCUT SIDEBAR + PAGE CONTENT) -->
        <div class="d-flex flex-grow-1 position-relative" style="min-height: calc(100vh - 110px);">
            <!-- SHORTCUT SIDEBAR (KOTAK SHORTCUT WARNA GREY) -->
            <aside id="shortcut-sidebar" class="shortcut-sidebar border-end" aria-label="Menu Shortcut">
                <div class="shortcut-header">
                    <div class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" />
                        </svg>
                        <span class="shortcut-header-title">{{ __('Shortcut') }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-sm btn-icon btn-ghost-primary rounded-circle" id="btn-open-shortcut-modal" data-bs-toggle="modal" data-bs-target="#modal-add-shortcut" title="Tambah Shortcut">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        </button>
                        <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary rounded-circle d-none d-md-inline-flex" id="btn-toggle-shortcut" title="Perkecil / Perbesar Sidebar">
                            <svg id="icon-collapse-shortcut" xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Shortcut Items Container -->
                <div id="shortcut-items-container" class="shortcut-items-container">
                    <!-- Rendered by JS -->
                </div>
            </aside>

            <!-- MAIN CONTENT WRAPPER -->
            <div class="page-wrapper flex-grow-1 d-flex flex-column" style="min-width: 0;">
                <div class="flex-grow-1">
                    @yield('content')
                </div>

                <!-- Tabler Footer -->
                <footer class="footer footer-transparent d-print-none mt-auto py-3 border-top">
                    <div class="container-fluid px-3 px-md-4">
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
    </div>

    <!-- MODAL TAMBAH / KELOLA SHORTCUT -->
    <div class="modal modal-blur fade" id="modal-add-shortcut" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" />
                        </svg>
                        <span>{{ __('Kelola Shortcut Menu') }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small mb-3">
                        Pilih menu dari <strong>Dashboard</strong> atau <strong>Setting</strong> untuk dijadikan pintasan cepat di kotak shortcut sebelah kiri.
                    </p>
                    <div class="mb-3">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                            </span>
                            <input type="text" id="shortcut-search-input" class="form-control" placeholder="Cari menu...">
                        </div>
                    </div>
                    <div class="btn-group w-100 mb-3" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm active" data-filter="all">Semua Menu</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-filter="Dashboard">Dashboard</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-filter="Setting">Setting</button>
                    </div>
                    <div id="modal-shortcut-list" class="list-group list-group-flush border rounded-2" style="max-height: 380px; overflow-y: auto;">
                        <!-- Rendered by JS -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Selesai</button>
                </div>
            </div>
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

            initShortcutSidebar();
        });

        // ================= SHORTCUT MANAGER =================
        const SHORTCUT_CATALOG = [
            {
                id: 'curah-hujan',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Curah Hujan") }}',
                url: '{{ route("dashboard.curah-hujan") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-12" /><path d="M11 13v2m0 3v.01" /><path d="M15 13v2m0 3v.01" /></svg>'
            },
            {
                id: 'aws',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("AWS") }}',
                url: '{{ route("dashboard.aws") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.657 18c-2.572 0 -4.657 -2.007 -4.657 -4.483c0 -2.475 2.085 -4.482 4.657 -4.482c.393 -1.762 1.794 -3.2 3.675 -3.773c1.88 -.572 3.956 -.16 5.433 1.074c1.601 .12 3.091 .998 3.974 2.34c.884 1.343 1.036 3.05 .405 4.542" /><path d="M12 16l0 4" /><path d="M8 18l8 0" /></svg>'
            },
            {
                id: 'areal-statement',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Areal Statement") }}',
                url: '{{ route("dashboard.areal-statement") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" /><path d="M9 4v13" /><path d="M15 7v13" /></svg>'
            },
            {
                id: 'produksi',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Produksi") }}',
                url: '{{ route("dashboard.produksi") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20l14 0" /></svg>'
            },
            {
                id: 'analisa-produksi-kebun',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Analisa Produksi Kebun") }}',
                url: '{{ route("dashboard.analisa-produksi-kebun") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 3.2a9 9 0 1 0 10.8 10.8a1 1 0 0 0 -1 -1h-6.8a1 1 0 0 1 -1 -1v-6.8a1 1 0 0 0 -1 -1" /><path d="M15 3.5a9 9 0 0 1 5.5 5.5h-4.5a1 1 0 0 1 -1 -1v-4.5" /></svg>'
            },
            {
                id: 'pemupukan-perawatan',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Pemupukan & Perawatan") }}',
                url: '{{ route("dashboard.pemupukan-perawatan") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21c.5 -4.5 2.5 -8 7 -10" /><path d="M9 18c6.218 0 10.5 -3.288 11 -12v-2h-4.014c-9 0 -11.986 7.986 -11.986 14v1h5.014" /></svg>'
            },
            {
                id: 'fleet-cartrack',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Fleet Cartrack") }}',
                url: '{{ route("dashboard.fleet-cartrack") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>'
            },
            {
                id: 'operasional-unit',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Operasional Unit") }}',
                url: '{{ route("dashboard.operasional-unit") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M9 8l1 0" /><path d="M9 12l1 0" /><path d="M9 16l1 0" /><path d="M14 8l1 0" /><path d="M14 12l1 0" /><path d="M14 16l1 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>'
            },
            {
                id: 'infrastruktur-jalan',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Infrastruktur Jalan") }}',
                url: '{{ route("dashboard.infrastruktur-jalan") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 19l4 -14" /><path d="M16 5l4 14" /><path d="M12 8v-2" /><path d="M12 13v-2" /><path d="M12 18v-2" /></svg>'
            },
            {
                id: 'sampel-daun-lsu',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Sampel Daun (LSU)") }}',
                url: '{{ route("dashboard.sampel-daun-lsu") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21c.5 -4.5 2.5 -8 7 -10" /><path d="M9 18c6.218 0 10.5 -3.288 11 -12v-2h-4.014c-9 0 -11.986 7.986 -11.986 14v1h5.014" /></svg>'
            },
            {
                id: 'assessmen-blok',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Assessmen Blok") }}',
                url: '{{ route("dashboard.assessmen-blok") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>'
            },
            {
                id: 'pembibitan',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Pembibitan") }}',
                url: '{{ route("dashboard.pembibitan") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 10a6 6 0 0 0 -6 -6h-3v2a6 6 0 0 0 6 6h3" /><path d="M12 14a6 6 0 0 1 6 -6h3v1a6 6 0 0 1 -6 6h-3" /><path d="M12 20l0 -10" /></svg>'
            },
            {
                id: 'legal',
                group: '{{ __("Dashboard") }}',
                title: '{{ __("Legal") }}',
                url: '{{ route("dashboard.legal") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>'
            },
            {
                id: 'users',
                group: '{{ __("Setting") }}',
                title: '{{ __("Pengguna") }}',
                url: '{{ route("admin.users.index") }}',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>'
            }
        ];

        const STORAGE_KEY_SHORTCUTS = 'dashboard_user_shortcuts';
        const STORAGE_KEY_COLLAPSE = 'shortcut_sidebar_collapsed';
        const DEFAULT_SHORTCUTS = ['curah-hujan', 'produksi', 'areal-statement', 'users'];

        function getSavedShortcuts() {
            try {
                const data = localStorage.getItem(STORAGE_KEY_SHORTCUTS);
                if (data) {
                    const parsed = JSON.parse(data);
                    if (Array.isArray(parsed)) return parsed;
                }
            } catch (e) {}
            return [...DEFAULT_SHORTCUTS];
        }

        function saveShortcuts(list) {
            try {
                localStorage.setItem(STORAGE_KEY_SHORTCUTS, JSON.stringify(list));
            } catch (e) {}
            renderShortcutsSidebar();
            renderModalShortcuts();
        }

        window.addShortcut = function(id) {
            const list = getSavedShortcuts();
            if (!list.includes(id)) {
                list.push(id);
                saveShortcuts(list);
            }
        };

        window.removeShortcut = function(id) {
            let list = getSavedShortcuts();
            list = list.filter(item => item !== id);
            saveShortcuts(list);
        };

        function renderShortcutsSidebar() {
            const container = document.getElementById('shortcut-items-container');
            if (!container) return;

            const savedIds = getSavedShortcuts();
            const currentUrl = window.location.pathname;

            if (savedIds.length === 0) {
                container.innerHTML = `
                    <div class="shortcut-empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        <p class="mb-2">Belum ada shortcut</p>
                        <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1" data-bs-toggle="modal" data-bs-target="#modal-add-shortcut">
                            + Tambah
                        </button>
                    </div>
                `;
                return;
            }

            let html = '';
            savedIds.forEach(id => {
                const item = SHORTCUT_CATALOG.find(m => m.id === id);
                if (!item) return;

                const itemUrl = new URL(item.url, window.location.origin).pathname;
                const isActive = (currentUrl === itemUrl || (itemUrl !== '/dashboard' && currentUrl.startsWith(itemUrl)));

                html += `
                    <div class="shortcut-item ${isActive ? 'active' : ''}" data-shortcut-id="${item.id}" title="${item.title} (${item.group})">
                        <a href="${item.url}" class="shortcut-item-link">
                            ${item.icon}
                            <span class="shortcut-item-text">${item.title}</span>
                        </a>
                        <button type="button" class="shortcut-item-remove" onclick="removeShortcut('${item.id}'); event.preventDefault(); event.stopPropagation();" title="Hapus shortcut">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                        </button>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        let currentModalFilter = 'all';
        let currentModalSearch = '';

        function renderModalShortcuts() {
            const listContainer = document.getElementById('modal-shortcut-list');
            if (!listContainer) return;

            const savedIds = getSavedShortcuts();
            const filtered = SHORTCUT_CATALOG.filter(item => {
                const matchesFilter = (currentModalFilter === 'all' || item.group === currentModalFilter);
                const matchesSearch = item.title.toLowerCase().includes(currentModalSearch.toLowerCase()) || item.group.toLowerCase().includes(currentModalSearch.toLowerCase());
                return matchesFilter && matchesSearch;
            });

            if (filtered.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        Tidak ada menu yang sesuai dengan pencarian.
                    </div>
                `;
                return;
            }

            let html = '';
            filtered.forEach(item => {
                const isAdded = savedIds.includes(item.id);
                html += `
                    <div class="list-group-item d-flex align-items-center justify-content-between p-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-secondary">${item.icon}</span>
                            <div>
                                <div class="fw-semibold small">${item.title}</div>
                                <span class="badge ${item.group === 'Dashboard' ? 'bg-blue-lt' : 'bg-purple-lt'} text-uppercase" style="font-size: 0.65rem;">
                                    ${item.group}
                                </span>
                            </div>
                        </div>
                        <div>
                            ${isAdded ? `
                                <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" onclick="removeShortcut('${item.id}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                    Hapus
                                </button>
                            ` : `
                                <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" onclick="addShortcut('${item.id}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Tambah
                                </button>
                            `}
                        </div>
                    </div>
                `;
            });

            listContainer.innerHTML = html;
        }

        function initShortcutSidebar() {
            renderShortcutsSidebar();
            renderModalShortcuts();

            const toggleBtn = document.getElementById('btn-toggle-shortcut');
            const sidebar = document.getElementById('shortcut-sidebar');
            const collapseIcon = document.getElementById('icon-collapse-shortcut');

            const isCollapsed = localStorage.getItem(STORAGE_KEY_COLLAPSE) === 'true';
            if (isCollapsed && sidebar) {
                sidebar.classList.add('collapsed');
                if (collapseIcon) {
                    collapseIcon.innerHTML = '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" />';
                }
            }

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    const nowCollapsed = sidebar.classList.toggle('collapsed');
                    localStorage.setItem(STORAGE_KEY_COLLAPSE, nowCollapsed ? 'true' : 'false');
                    if (collapseIcon) {
                        collapseIcon.innerHTML = nowCollapsed
                            ? '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" />'
                            : '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" />';
                    }
                });
            }

            // Mobile toggle
            const mobileBtn = document.getElementById('btn-mobile-shortcut');
            if (mobileBtn && sidebar) {
                mobileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show-mobile');
                });

                document.addEventListener('click', function(e) {
                    if (!sidebar.contains(e.target) && !mobileBtn.contains(e.target)) {
                        sidebar.classList.remove('show-mobile');
                    }
                });
            }

            // Modal filters & search
            const searchInput = document.getElementById('shortcut-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    currentModalSearch = e.target.value;
                    renderModalShortcuts();
                });
            }

            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentModalFilter = this.getAttribute('data-filter');
                    renderModalShortcuts();
                });
            });
        }
    </script>
</body>

</html>
