@extends('layouts.app')

@section('title', 'Masuk - AI Dashboard')

@section('body-class', 'd-flex flex-column bg-body-tertiary')

@section('content')
    <div class="page page-center py-4">
        <div class="container container-tight py-3 my-auto">
            <!-- Logo / Brand Header -->
            <div class="text-center mb-4">
                <a href="{{ url('/') }}"
                    class="navbar-brand navbar-brand-autodark d-inline-flex align-items-center gap-2 text-decoration-none">
                    <span class="avatar avatar-rounded bg-primary text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-sparkles" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6z" />
                        </svg>
                    </span>
                    <span class="fs-2 fw-bold text-body">AI Dashboard - PT GUM</span>
                </a>
            </div>

            <!-- Login Card -->
            <div class="card card-md shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h2 text-center mb-2">Masuk ke Akun Anda</h2>
                    <p class="text-secondary text-center mb-4">Silakan masukkan kredensial Anda untuk melanjutkan</p>

                    <!-- Feedback status / alert jika ada -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                </div>
                                <div>{{ session('status') }}</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 9v4" />
                                        <path
                                            d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                        <path d="M12 16h.01" />
                                    </svg>
                                </div>
                                <div>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" autocomplete="off" novalidate>
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label required" for="email">Alamat Email</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                        <path d="M3 7l9 6l9 -6" />
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="nama@perusahaan.com" value="{{ old('email') }}" required autofocus
                                    autocomplete="username">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label class="form-label required" for="password">
                                Kata Sandi
                                <span class="form-label-description">
                                    <a href="#" class="text-muted">Lupa sandi?</a>
                                </span>
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                    autocomplete="current-password" required>
                                <span class="input-group-text">
                                    <a href="javascript:void(0)" id="toggle-password" class="link-secondary"
                                        title="Lihat kata sandi">
                                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                        <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="icon d-none"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                                            <path
                                                d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                                            <path d="M3 3l18 18" />
                                        </svg>
                                    </a>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="mb-3">
                            <label class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember"
                                    {{ old('remember') ? 'checked' : '' }} />
                                <span class="form-check-label user-select-none">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-footer">
                            <button type="submit"
                                class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M15 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M21 12h-13l3 -3" />
                                    <path d="M11 15l-3 -3" />
                                </svg>
                                <span>Masuk</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer / IT Contact -->
            <div class="text-center text-secondary mt-3">
                <span class="d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-headset text-muted" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 14v-3a8 8 0 0 1 16 0v3" />
                        <path d="M18 19c0 1.657 -2.686 3 -6 3" />
                        <path d="M4 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" />
                        <path d="M15 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" />
                    </svg>
                    Kontak IT Untuk Akses Masuk Sistem
                </span>
            </div>

            <!-- Theme Toggle (Light / Dark) -->
            <div class="text-center mt-3">
                <button type="button" id="theme-toggle-btn"
                    class="btn btn-sm btn-ghost-secondary rounded-pill d-inline-flex align-items-center gap-1">
                    <svg id="theme-icon-sun" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-sun"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path
                            d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                    </svg>
                    <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-moon d-none" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                    </svg>
                    <span id="theme-toggle-text">Mode Gelap</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Password Visibility
            const toggleBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    eyeIcon.classList.toggle('d-none', isPassword);
                    eyeOffIcon.classList.toggle('d-none', !isPassword);
                });
            }

            // Dark / Light Theme Toggle
            const themeBtn = document.getElementById('theme-toggle-btn');
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            const themeText = document.getElementById('theme-toggle-text');

            function applyTheme(theme) {
                if (!theme) theme = 'light';
                // Set attributes on both <html> and <body>
                document.documentElement.setAttribute('data-bs-theme', theme);
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.style.colorScheme = theme;
                if (document.body) {
                    document.body.setAttribute('data-bs-theme', theme);
                    document.body.setAttribute('data-theme', theme);
                }

                if (theme === 'dark') {
                    document.documentElement.classList.add('theme-dark');
                    if (document.body) document.body.classList.add('theme-dark');
                    if (sunIcon) sunIcon.classList.remove('d-none');
                    if (moonIcon) moonIcon.classList.add('d-none');
                    if (themeText) themeText.textContent = 'Mode Terang';
                } else {
                    document.documentElement.classList.remove('theme-dark');
                    if (document.body) document.body.classList.remove('theme-dark');
                    if (sunIcon) sunIcon.classList.add('d-none');
                    if (moonIcon) moonIcon.classList.remove('d-none');
                    if (themeText) themeText.textContent = 'Mode Gelap';
                }

                try {
                    localStorage.setItem('tabler-theme', theme);
                } catch (e) {}
            }

            const savedTheme = localStorage.getItem('tabler-theme') || 'light';
            applyTheme(savedTheme);

            if (themeBtn) {
                themeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
                    applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
                });
            }
        });
    </script>
@endpush
