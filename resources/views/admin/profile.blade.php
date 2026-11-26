@extends('layouts.admin')

@section('title', __('Profil Pengguna') . ' - PT GUM AI')

@section('content')
<!-- Page Header -->
<div class="page-header d-print-none py-3">
    <div class="container-fluid px-3 px-md-4">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-secondary">
                    {{ __('Pengaturan Akun') }}
                </div>
                <h2 class="page-title text-body fw-bold">
                    {{ __('Profil Pengguna') }}
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Page Body -->
<div class="page-body">
    <div class="container-fluid px-3 px-md-4">
        @if (session('success'))
            <div class="alert alert-important alert-success alert-dismissible shadow-sm mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon pe-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </div>
                    <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible shadow-sm mb-3" role="alert">
                <div class="fw-bold mb-1">{{ __('Terjadi kesalahan input:') }}</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <div class="row row-cards">
            <!-- Sidebar Card User Info -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <span class="avatar avatar-xl avatar-rounded bg-primary-lt fw-bold text-primary fs-1 mb-3">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                        <h3 class="m-0 mb-1 fw-bold">{{ $user->name }}</h3>
                        <div class="text-secondary small mb-2">{{ $user->email }}</div>
                        <div>
                            @if ($user->role === 'admin')
                                <span class="badge bg-purple-lt text-uppercase fw-semibold">Admin</span>
                            @elseif ($user->role === 'operator')
                                <span class="badge bg-azure-lt text-uppercase fw-semibold">Operator</span>
                            @else
                                <span class="badge bg-secondary-lt text-uppercase fw-semibold">{{ $user->role }}</span>
                            @endif
                            <span class="badge {{ $user->is_active ? 'bg-success-lt' : 'bg-danger-lt' }} ms-1 fw-semibold">
                                {{ $user->is_active ? __('Aktif') : __('Nonaktif') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-body-tertiary">
                        <div class="row text-secondary small gy-2">
                            <div class="col-12 d-flex justify-content-between">
                                <span>{{ __('Login Terakhir') }}:</span>
                                <span class="fw-semibold text-body">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '-' }}</span>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <span>{{ __('IP Terakhir') }}:</span>
                                <span class="font-monospace text-body">{{ $user->last_login_ip ?? '-' }}</span>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <span>{{ __('Terdaftar') }}:</span>
                                <span class="text-body">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Edit Form Card -->
            <div class="col-lg-8">
                <form method="POST" action="{{ route('profile.update') }}" class="card shadow-sm border-0">
                    @csrf
                    @method('PATCH')
                    <div class="card-header border-bottom">
                        <h3 class="card-title fw-bold">{{ __('Informasi Akun') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('Nama Lengkap') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('Alamat Email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Nomor HP') }}</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Role Akses') }}</label>
                                <input type="text" class="form-control bg-body-tertiary" value="{{ ucfirst($user->role) }}" disabled>
                                <small class="form-hint">{{ __('Role diatur oleh IT Administrator.') }}</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h3 class="card-title fw-bold mb-3 d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                            {{ __('Ubah Kata Sandi') }}
                        </h3>
                        <p class="text-secondary small mb-3">
                            {{ __('Kosongkan bagian ini jika Anda tidak ingin mengubah kata sandi.') }}
                        </p>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ __('Kata Sandi Saat Ini') }}</label>
                                <input type="password" name="current_password" class="form-control" placeholder="{{ __('Masukkan kata sandi saat ini jika ingin mengganti') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Kata Sandi Baru') }}</label>
                                <input type="password" name="password" class="form-control" placeholder="{{ __('Minimal 6 karakter') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Konfirmasi Kata Sandi Baru') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Ulangi kata sandi baru') }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end bg-body-tertiary border-top">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                            {{ __('Simpan Perubahan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
