@extends('layouts.admin')

@section('title', __('Dashboard Analisa Produksi Kebun') . ' - PT GUM AI')

@push('styles')
<style>
    .powerbi-wrapper {
        position: relative;
        width: 100%;
        min-height: calc(100vh - 16rem);
        background-color: var(--tblr-bg-surface);
        border-radius: var(--tblr-border-radius);
        overflow: hidden;
    }
    .powerbi-frame {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
    .powerbi-wrapper.is-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 1060;
        border-radius: 0;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header d-print-none py-3">
    <div class="container-fluid px-3 px-md-4">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-secondary">
                    {{ __('Power BI Analytics') }}
                </div>
                <h2 class="page-title text-body fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M20 18v3" /><path d="M16 16v5" /><path d="M12 13v8" /><path d="M8 16v5" /><path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" /></svg>
                    {{ __('Dashboard Analisa Produksi Kebun') }}
                </h2>
            </div>
            <!-- Actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1" id="btn-fullscreen" title="{{ __('Layar Penuh') }}">
                        <svg id="icon-fs-enter" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8v-2a2 2 0 0 1 2 -2h2" /><path d="M4 16v2a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v2" /><path d="M16 20h2a2 2 0 0 0 2 -2v-2" /></svg>
                        <svg id="icon-fs-exit" xmlns="http://www.w3.org/2000/svg" class="icon d-none" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 19v-2a2 2 0 0 1 2 -2h2" /><path d="M15 5v2a2 2 0 0 0 2 2h2" /><path d="M5 15h2a2 2 0 0 1 2 2v2" /><path d="M5 9h2a2 2 0 0 0 2 -2v-2" /></svg>
                        <span id="text-fs">{{ __('Layar Penuh') }}</span>
                    </button>
                    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1" onclick="document.getElementById('pbi-iframe').src = document.getElementById('pbi-iframe').src">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5" /></svg>
                        {{ __('Muat Ulang') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Body -->
<div class="page-body mt-2">
    <div class="container-fluid px-3 px-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="powerbi-wrapper shadow-none" id="powerbi-container">
                    <iframe id="pbi-iframe"
                        class="powerbi-frame"
                        title="Dashboard Analisa Produksi Kebun - Power BI"
                        src="{{ $embedUrl }}"
                        allowfullscreen="true">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnFs = document.getElementById('btn-fullscreen');
        const container = document.getElementById('powerbi-container');
        const iconEnter = document.getElementById('icon-fs-enter');
        const iconExit = document.getElementById('icon-fs-exit');
        const textFs = document.getElementById('text-fs');

        if (!btnFs || !container) return;

        btnFs.addEventListener('click', function () {
            if (!document.fullscreenElement) {
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        });

        document.addEventListener('fullscreenchange', function () {
            const isFs = !!document.fullscreenElement;
            container.classList.toggle('is-fullscreen', isFs);
            if (iconEnter) iconEnter.classList.toggle('d-none', isFs);
            if (iconExit) iconExit.classList.toggle('d-none', !isFs);
            if (textFs) textFs.textContent = isFs ? '{{ __("Keluar Penuh") }}' : '{{ __("Layar Penuh") }}';
        });
    });
</script>
@endpush
