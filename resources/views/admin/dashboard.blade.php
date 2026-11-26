@extends('layouts.admin')

@section('title', 'Admin Dashboard - PT GUM AI')

@section('content')
<!-- Page Header -->
<div class="page-header d-print-none py-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-secondary">
                    {{ __('Dashboard Power BI + AI Analytics') }}
                </div>
                <h2 class="page-title text-body fw-bold">
                    {{ __('Selamat Datang, :name', ['name' => $currentUser->name]) }}
                </h2>
            </div>
            <!-- Quick Actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1" onclick="window.location.reload()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5" /></svg>
                        {{ __('Refresh Data') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Body -->
<div class="page-body">
    <div class="container-xl">
        <!-- Empty dashboard content - only footer remains via layout -->
    </div>
</div>
@endsection
