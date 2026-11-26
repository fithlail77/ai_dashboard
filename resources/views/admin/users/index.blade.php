@extends('layouts.admin')

@section('title', __('Manajemen Pengguna') . ' - PT GUM AI')

@push('styles')
<style>
    .editable-cell {
        cursor: pointer;
        position: relative;
        transition: background-color 0.15s ease-in-out;
    }
    .editable-cell:hover {
        background-color: var(--tblr-primary-lt) !important;
    }
    .editable-cell::after {
        content: '✎';
        font-size: 0.65rem;
        opacity: 0;
        margin-left: 0.35rem;
        color: var(--tblr-primary);
        transition: opacity 0.15s;
    }
    .editable-cell:hover::after {
        opacity: 0.7;
    }
    .inline-edit-input {
        min-width: 130px;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header d-print-none py-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-secondary">
                    {{ __('Pengaturan Sistem') }}
                </div>
                <h2 class="page-title text-body fw-bold">
                    {{ __('Manajemen Pengguna') }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modal-create-user">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    {{ __('Tambah Pengguna') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Page Body -->
<div class="page-body">
    <div class="container-xl">
        <!-- Toast / Inline Notification Notification Hub -->
        <div id="inline-alert" class="alert alert-important d-none alert-dismissible shadow-sm mb-3" role="alert">
            <span id="inline-alert-text"></span>
            <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('inline-alert').classList.add('d-none')"></button>
        </div>

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

        @if (session('error'))
            <div class="alert alert-important alert-danger alert-dismissible shadow-sm mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon pe-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                    </div>
                    <div>{{ session('error') }}</div>
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

        <!-- Card Table Tabler -->
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom py-3">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center w-100">
                    <div class="col-md-4">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                            </span>
                            <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="{{ __('Cari nama, email, nomor HP...') }}">
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-2">
                        <select name="role" class="form-select form-select-sm">
                            <option value="">{{ __('Semua Role') }}</option>
                            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ $role === 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="viewer" {{ $role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                    </div>
                    <div class="col-sm-3 col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('Semua Status') }}</option>
                            <option value="1" {{ $status === '1' ? 'selected' : '' }}>{{ __('Aktif') }}</option>
                            <option value="0" {{ $status === '0' ? 'selected' : '' }}>{{ __('Nonaktif') }}</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('Filter') }}</button>
                        @if ($search || $role || $status !== null && $status !== '')
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-ghost-secondary">{{ __('Reset') }}</a>
                        @endif
                    </div>
                    <div class="col-auto ms-auto small text-muted d-none d-lg-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                        <span class="fst-italic">{{ __('Klik 2x pada sel tabel untuk edit langsung, tekan Enter untuk simpan.') }}</span>
                    </div>
                </form>
            </div>

            <!-- Tabler Standard Table -->
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="w-1">ID</th>
                            <th>{{ __('Pengguna') }}</th>
                            <th>{{ __('Kontak') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Login Terakhir') }}</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        @forelse ($users as $user)
                            <tr id="user-row-{{ $user->id }}" data-user-id="{{ $user->id }}">
                                <td class="text-secondary font-monospace">#{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <span id="user-avatar-{{ $user->id }}" class="avatar avatar-sm me-2 bg-primary-lt fw-bold text-primary">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                        <div class="flex-fill">
                                            <div class="font-weight-medium fw-semibold editable-cell"
                                                data-field="name"
                                                data-type="text"
                                                data-value="{{ $user->name }}"
                                                title="{{ __('Klik 2x untuk edit nama') }}">
                                                <span class="cell-text">{{ $user->name }}</span>
                                            </div>
                                            <div class="text-secondary small editable-cell"
                                                data-field="email"
                                                data-type="text"
                                                data-value="{{ $user->email }}"
                                                title="{{ __('Klik 2x untuk edit email') }}">
                                                <span class="cell-text">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-secondary small editable-cell"
                                        data-field="phone"
                                        data-type="text"
                                        data-value="{{ $user->phone ?? '' }}"
                                        title="{{ __('Klik 2x untuk edit nomor HP') }}">
                                        <span class="cell-text">{{ $user->phone ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="editable-cell d-inline-block"
                                        data-field="role"
                                        data-type="select"
                                        data-options="admin:Admin,operator:Operator,viewer:Viewer"
                                        data-value="{{ $user->role }}"
                                        title="{{ __('Klik 2x untuk ganti role') }}">
                                        <span class="cell-text">
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-purple-lt text-uppercase fw-semibold">Admin</span>
                                            @elseif ($user->role === 'operator')
                                                <span class="badge bg-azure-lt text-uppercase fw-semibold">Operator</span>
                                            @else
                                                <span class="badge bg-secondary-lt text-uppercase fw-semibold">{{ $user->role }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="editable-cell d-inline-block"
                                        data-field="is_active"
                                        data-type="select"
                                        data-options="1:Aktif,0:Nonaktif"
                                        data-value="{{ $user->is_active ? '1' : '0' }}"
                                        title="{{ __('Klik 2x untuk ganti status') }}">
                                        <span class="cell-text">
                                            @if ($user->is_active)
                                                <span class="badge bg-success-lt fw-semibold">{{ __('Aktif') }}</span>
                                            @else
                                                <span class="badge bg-danger-lt fw-semibold">{{ __('Nonaktif') }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '-' }}
                                    @if ($user->last_login_ip)
                                        <div class="text-muted font-monospace small">IP: {{ $user->last_login_ip }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users mb-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                    <div>{{ __('Tidak ada data pengguna ditemukan.') }}</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tabler Pagination Footer -->
            @if ($users->hasPages())
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-secondary small">
                        {{ __('Menampilkan') }} <span>{{ $users->firstItem() }}</span> {{ __('sampai') }} <span>{{ $users->lastItem() }}</span> {{ __('dari') }} <span>{{ $users->total() }}</span> {{ __('pengguna') }}
                    </p>
                    <div class="ms-auto">
                        {{ $users->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Create User -->
<div class="modal modal-blur fade" id="modal-create-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('admin.users.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">{{ __('Tambah Pengguna Baru') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required">{{ __('Nama Lengkap') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: John Doe" required>
                </div>
                <div class="mb-3">
                    <label class="form-label required">{{ __('Alamat Email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@gum.co.id" required>
                </div>
                <div class="mb-3">
                    <label class="form-label required">{{ __('Kata Sandi') }}</label>
                    <input type="password" name="password" class="form-control" placeholder="{{ __('Minimal 6 karakter') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">{{ __('Role') }}</label>
                        <select name="role" class="form-select" required>
                            <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="viewer" {{ old('role', 'viewer') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Nomor HP') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <span class="form-check-label">{{ __('Status Akun Aktif') }}</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Simpan Pengguna') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || '{{ csrf_token() }}';

        function showToast(message, isSuccess = true) {
            const alertBox = document.getElementById('inline-alert');
            const alertText = document.getElementById('inline-alert-text');
            if (!alertBox || !alertText) return;

            alertBox.className = 'alert alert-important alert-dismissible shadow-sm mb-3 alert-' + (isSuccess ? 'success' : 'danger');
            alertText.textContent = message;
            alertBox.classList.remove('d-none');

            setTimeout(() => {
                alertBox.classList.add('d-none');
            }, 3500);
        }

        document.querySelectorAll('.editable-cell').forEach(cell => {
            cell.addEventListener('dblclick', function () {
                if (this.dataset.isEditing === 'true') return;
                this.dataset.isEditing = 'true';

                const field = this.dataset.field;
                const type = this.dataset.type;
                const currentValue = this.dataset.value;
                const row = this.closest('tr');
                const userId = row.dataset.userId;

                const originalHTML = this.innerHTML;

                let inputEl;
                if (type === 'select') {
                    inputEl = document.createElement('select');
                    inputEl.className = 'form-select form-select-sm inline-edit-input';
                    const options = this.dataset.options.split(',');
                    options.forEach(opt => {
                        const [val, label] = opt.split(':');
                        const optionEl = document.createElement('option');
                        optionEl.value = val;
                        optionEl.textContent = label;
                        if (val === currentValue) optionEl.selected = true;
                        inputEl.appendChild(optionEl);
                    });
                } else {
                    inputEl = document.createElement('input');
                    inputEl.type = 'text';
                    inputEl.value = currentValue;
                    inputEl.className = 'form-control form-control-sm inline-edit-input';
                }

                this.innerHTML = '';
                this.appendChild(inputEl);
                inputEl.focus();
                if (inputEl.select) inputEl.select();

                const cancelEdit = () => {
                    this.innerHTML = originalHTML;
                    this.dataset.isEditing = 'false';
                };

                const saveEdit = async () => {
                    const newValue = inputEl.value.trim();
                    if (newValue === currentValue) {
                        cancelEdit();
                        return;
                    }

                    inputEl.disabled = true;
                    try {
                        const response = await fetch(`/settings/users/${userId}/inline`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                field: field,
                                value: newValue
                            })
                        });

                        const res = await response.json();
                        if (!response.ok) {
                            const errorMsg = res.message || (res.errors ? Object.values(res.errors)[0][0] : 'Gagal menyimpan.');
                            showToast(errorMsg, false);
                            cancelEdit();
                            return;
                        }

                        // Update dataset value
                        this.dataset.value = newValue;
                        this.dataset.isEditing = 'false';

                        // Render updated badge / text
                        if (field === 'role') {
                            let badgeClass = 'bg-secondary-lt';
                            if (newValue === 'admin') badgeClass = 'bg-purple-lt';
                            else if (newValue === 'operator') badgeClass = 'bg-azure-lt';
                            this.innerHTML = `<span class="cell-text"><span class="badge ${badgeClass} text-uppercase fw-semibold">${newValue}</span></span>`;
                        } else if (field === 'is_active') {
                            const isActive = (newValue === '1' || newValue === 'true');
                            const badgeClass = isActive ? 'bg-success-lt' : 'bg-danger-lt';
                            const badgeText = isActive ? '{{ __("Aktif") }}' : '{{ __("Nonaktif") }}';
                            this.innerHTML = `<span class="cell-text"><span class="badge ${badgeClass} fw-semibold">${badgeText}</span></span>`;
                        } else {
                            this.innerHTML = `<span class="cell-text">${newValue || '-'}</span>`;
                        }

                        // If name changed, update avatar initial too
                        if (field === 'name' && res.data?.initials) {
                            const avatar = document.getElementById(`user-avatar-${userId}`);
                            if (avatar) avatar.textContent = res.data.initials;
                        }

                        showToast(res.message || 'Data berhasil diperbarui.', true);
                    } catch (err) {
                        showToast('Koneksi bermasalah: ' + err.message, false);
                        cancelEdit();
                    }
                };

                inputEl.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveEdit();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        cancelEdit();
                    }
                });

                inputEl.addEventListener('blur', function () {
                    if (cell.dataset.isEditing === 'true') {
                        cancelEdit();
                    }
                });
            });
        });
    });
</script>
@endpush
