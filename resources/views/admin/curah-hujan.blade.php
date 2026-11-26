@extends('layouts.admin')

@section('title', __('Dashboard Curah Hujan') . ' - PT GUM AI')

@push('styles')
<!-- CDN Libraries untuk visualisasi AI Assistant & Export -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.9/dist/purify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<style>
    .powerbi-wrapper {
        position: relative;
        width: 100%;
        height: calc(100vh - 14.5rem);
        min-height: 520px;
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

    /* Panel AI Chat Assistant */
    .assistant-card {
        height: calc(100vh - 14.5rem);
        min-height: 520px;
        display: flex;
        flex-direction: column;
    }

    .assistant-chat-box {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 0.85rem;
        background-color: rgba(var(--tblr-body-bg-rgb), 0.5);
    }

    .assistant-chat-box::-webkit-scrollbar {
        width: 6px;
    }
    .assistant-chat-box::-webkit-scrollbar-track {
        background: transparent;
    }
    .assistant-chat-box::-webkit-scrollbar-thumb {
        background: var(--tblr-border-color);
        border-radius: 3px;
    }

    .assistant-input-area {
        border-top: 1px solid var(--tblr-border-color);
        background: var(--tblr-bg-surface);
        padding: 0.65rem 0.75rem;
    }


    /* Bubble Chat Styles */
    .chat-bubble-user {
        background-color: var(--tblr-primary);
        color: #ffffff;
        padding: 0.55rem 0.85rem;
        border-radius: 1rem 1rem 0.2rem 1rem;
        font-size: 0.825rem;
        line-height: 1.4;
        max-width: 88%;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    }

    .chat-bubble-ai {
        background-color: var(--tblr-bg-surface);
        color: var(--tblr-body-color);
        border: 1px solid var(--tblr-border-color);
        padding: 0.7rem 0.9rem;
        border-radius: 1rem 1rem 1rem 0.2rem;
        font-size: 0.825rem;
        line-height: 1.45;
        width: 100%;
        max-width: 98%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow-x: auto;
    }

    /* Markdown Styling in AI Bubble */
    .ai-markdown h1,
    .ai-markdown h2,
    .ai-markdown h3 {
        font-weight: 700;
        margin-top: 0.4rem;
        margin-bottom: 0.4rem;
        line-height: 1.3;
    }
    .ai-markdown h2 {
        font-size: 0.9rem;
        border-bottom: 1px solid var(--tblr-border-color);
        padding-bottom: 0.2rem;
    }
    .ai-markdown h3 {
        font-size: 0.825rem;
    }
    .ai-markdown p {
        margin-bottom: 0.45rem;
    }
    .ai-markdown p:last-child {
        margin-bottom: 0;
    }
    .ai-markdown ul,
    .ai-markdown ol {
        padding-left: 1.25rem;
        margin-bottom: 0.45rem;
    }
    .ai-markdown li {
        margin-bottom: 0.2rem;
    }
    .ai-markdown table {
        width: 100%;
        border-collapse: collapse;
        margin: 0.45rem 0;
        font-size: 0.75rem;
        border: 1px solid var(--tblr-border-color);
        border-radius: 6px;
        overflow: hidden;
    }
    .ai-markdown thead tr {
        background-color: var(--tblr-bg-surface-secondary);
        font-weight: 600;
        border-bottom: 1px solid var(--tblr-border-color);
    }
    .ai-markdown th,
    .ai-markdown td {
        padding: 0.35rem 0.55rem;
        border-bottom: 1px solid var(--tblr-border-color);
        border-right: 1px solid var(--tblr-border-color-translucent, rgba(0,0,0,0.05));
    }
    .ai-markdown th:last-child,
    .ai-markdown td:last-child {
        border-right: none;
    }
    .ai-markdown tbody tr:nth-child(even) {
        background-color: rgba(var(--tblr-body-color-rgb), 0.02);
    }
    .ai-markdown tbody tr:hover {
        background-color: var(--tblr-primary-lt);
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-12" /><path d="M11 13v2m0 3v.01" /><path d="M15 13v2m0 3v.01" /></svg>
                    {{ __('Dashboard Curah Hujan') }}
                </h2>
            </div>
            <!-- Actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1" id="btn-fullscreen" title="{{ __('Layar Penuh') }}">
                        <svg id="icon-fs-enter" xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8v-2a2 2 0 0 1 2 -2h2" /><path d="M4 16v2a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v2" /><path d="M16 20h2a2 2 0 0 0 2 -2v-2" /></svg>
                        <svg id="icon-fs-exit" xmlns="http://www.w3.org/2000/svg" class="icon d-none" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 19v-2a2 2 0 0 1 2 -2h2" /><path d="M15 5v2a2 2 0 0 0 2 2h2" /><path d="M5 15h2a2 2 0 0 1 2 2v2" /><path d="M5 9h2a2 2 0 0 0 2 -2v-2" /></svg>
                        <span id="text-fs">{{ __('Layar Penuh') }}</span>
                    </button>
                    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1" onclick="document.getElementById('pbi-iframe').src = document.getElementById('pbi-iframe').src">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5" /></svg>
                        {{ __('Muat Ulang') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Body: Bersebelahan (8 Kolom Power BI + 4 Kolom AI Assistant) -->
<div class="page-body mt-1">
    <div class="container-fluid px-3 px-md-4">
        <div class="row g-3">
            <!-- AREA KIRI (Kolom 8): Power BI Report -->
            <div class="col-12 col-xl-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-0">
                        <div class="powerbi-wrapper shadow-none" id="powerbi-container">
                            <iframe id="pbi-iframe"
                                class="powerbi-frame"
                                title="Dashboard Curah Hujan - Power BI"
                                src="{{ $embedUrl }}"
                                allowfullscreen="true">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AREA KANAN (Kolom 4): Panel Chat AI Assistant -->
            <div class="col-12 col-xl-4">
                <div class="card shadow-sm border-0 assistant-card">
                    <!-- Header Chat -->
                    <div class="card-header bg-dark text-white py-2 px-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm bg-primary text-white rounded">
                                AI
                            </div>
                            <div>
                                <h3 class="card-title text-white fs-4 mb-0 fw-semibold">Asisten AI Data Curah Hujan</h3>
                                <div class="text-white-50 small" style="font-size: 0.7rem;">Tanya jawab langsung ke Dataset Power BI</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-ghost-light text-white-50 px-2 py-1" onclick="clearChat()" title="Bersihkan Chat">
                            Reset
                        </button>
                    </div>


                    <!-- Riwayat Pesan Chat -->
                    <div id="chatBox" class="assistant-chat-box d-flex flex-column gap-2">
                        <!-- Pesan Sambutan Default -->
                        <div class="d-flex flex-column align-items-start">
                            <div class="chat-bubble-ai">
                                Halo! Saya terhubung dengan dataset <strong>Curah Hujan</strong>. Silakan tanyakan data curah hujan, perbandingan antar estate/PT, maupun hari hujan.
                            </div>
                        </div>
                    </div>

                    <!-- Input Form Chat -->
                    <form id="pbiChatForm" class="assistant-input-area d-flex align-items-end gap-2">
                        <div class="flex-grow-1 position-relative">
                            <textarea id="promptInput" rows="1" class="form-control" placeholder="Ketik pertanyaan analisis... (Shift+Enter baris baru)" style="min-height: 40px; max-height: 120px; resize: none; font-size: 0.8125rem;" required autocomplete="off"></textarea>
                        </div>
                        <button type="submit" id="submitBtn" class="btn btn-primary d-inline-flex align-items-center gap-1 px-3" style="min-height: 40px;">
                            <span>Kirim</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ==========================================
    // 1. FULLSCREEN & RELOAD HANDLER
    // ==========================================
    function triggerFullscreenPbi() {
        const container = document.getElementById('powerbi-container');
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
    }

    document.addEventListener('DOMContentLoaded', function () {
        const btnFs = document.getElementById('btn-fullscreen');
        const container = document.getElementById('powerbi-container');
        const iconEnter = document.getElementById('icon-fs-enter');
        const iconExit = document.getElementById('icon-fs-exit');
        const textFs = document.getElementById('text-fs');

        if (btnFs && container) {
            btnFs.addEventListener('click', triggerFullscreenPbi);

            document.addEventListener('fullscreenchange', function () {
                const isFs = !!document.fullscreenElement;
                container.classList.toggle('is-fullscreen', isFs);
                if (iconEnter) iconEnter.classList.toggle('d-none', isFs);
                if (iconExit) iconExit.classList.toggle('d-none', !isFs);
                if (textFs) textFs.textContent = isFs ? '{{ __("Keluar Penuh") }}' : '{{ __("Layar Penuh") }}';
            });
        }
    });

    // ==========================================
    // 2. LOGIKA CHAT AI POWER BI (TABLER UI)
    // ==========================================
    const chatForm = document.getElementById('pbiChatForm');
    const promptInput = document.getElementById('promptInput');
    const submitBtn = document.getElementById('submitBtn');
    const chatBox = document.getElementById('chatBox');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function autoResizeInput() {
        promptInput.style.height = 'auto';
        const maxHeight = 120;
        if (promptInput.scrollHeight > maxHeight) {
            promptInput.style.height = maxHeight + 'px';
            promptInput.style.overflowY = 'auto';
        } else {
            promptInput.style.height = Math.max(promptInput.scrollHeight, 40) + 'px';
            promptInput.style.overflowY = 'hidden';
        }
    }

    function resetInputHeight() {
        promptInput.style.height = '40px';
        promptInput.style.overflowY = 'hidden';
    }

    promptInput.addEventListener('input', autoResizeInput);

    promptInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey && !e.isComposing) {
            e.preventDefault();
            chatForm.requestSubmit();
        }
    });


    function clearChat() {
        chatBox.innerHTML = `
            <div class="text-center text-muted small my-3">Riwayat percakapan dibersihkan.</div>
        `;
        fetch("{{ route('powerbi.assistant.reset') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).catch(console.error);
    }

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const prompt = promptInput.value.trim();
        if (!prompt) return;

        appendUserBubble(prompt);
        promptInput.value = '';
        resetInputHeight();
        submitBtn.disabled = true;

        const loaderId = appendLoadingBubble();

        try {
            const response = await fetch("{{ route('powerbi.assistant.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ prompt })
            });

            const data = await response.json();
            document.getElementById(loaderId)?.remove();

            if (!response.ok || data.status === 'error') {
                appendErrorBubble(data.message || 'Gagal memproses permintaan ke 9Router / Power BI.');
            } else {
                appendAiBubble(data.reply, data.dax, data.model, data.row_count, data.self_healed);
            }
        } catch (err) {
            document.getElementById(loaderId)?.remove();
            appendErrorBubble('Koneksi terputus. Pastikan server Laravel dan 9Router aktif.');
        } finally {
            submitBtn.disabled = false;
            promptInput.focus();
            scrollToBottom();
        }
    });

    function appendUserBubble(text) {
        const cleanText = (text || '').trim();
        chatBox.insertAdjacentHTML('beforeend', `
            <div class="d-flex justify-content-end mb-2">
                <div class="chat-bubble-user text-break">${escapeHtml(cleanText)}</div>
            </div>
        `);
        scrollToBottom();
    }

    function downloadChart(canvasId, title) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const a = document.createElement('a');
        a.href = canvas.toDataURL('image/png');
        const safeTitle = (title || 'grafik-analisis').toLowerCase().replace(/[^a-z0-9]+/g, '-');
        a.download = `${safeTitle}.png`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    function exportTableToExcel(tableEl, baseName = 'data-analisis-curah-hujan') {
        if (!window.XLSX) {
            alert('Library SheetJS belum selesai dimuat.');
            return;
        }
        const wb = XLSX.utils.table_to_book(tableEl, { sheet: "Data Analisis" });
        const safeName = baseName.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        XLSX.writeFile(wb, `${safeName}.xlsx`);
    }

    function exportTableToPdf(tableEl, baseName = 'data-analisis-curah-hujan') {
        if (!window.jspdf || !window.jspdf.jsPDF) {
            alert('Library jsPDF belum selesai dimuat.');
            return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'pt', 'a4');

        doc.setFont("helvetica", "bold");
        doc.setFontSize(13);
        doc.setTextColor(30, 41, 59);
        doc.text("Laporan Ringkasan Analisis Data - Power BI AI", 40, 40);

        doc.setFont("helvetica", "normal");
        doc.setFontSize(9);
        doc.setTextColor(100);
        doc.text("Diunduh pada: " + new Date().toLocaleString('id-ID'), 40, 56);

        doc.autoTable({
            html: tableEl,
            startY: 70,
            theme: 'grid',
            styles: {
                fontSize: 8.5,
                cellPadding: 5,
                font: "helvetica",
            },
            headStyles: {
                fillColor: [15, 23, 42],
                textColor: 255,
                fontStyle: 'bold',
            },
            alternateRowStyles: {
                fillColor: [248, 250, 252],
            },
            margin: { top: 70, left: 40, right: 40 },
        });

        const safeName = baseName.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        doc.save(`${safeName}.pdf`);
    }

    function appendAiBubble(reply, dax, model, rowCount, selfHealed = false) {
        if (window.marked) {
            marked.setOptions({ gfm: true, breaks: true });
        }

        const chartsToRender = [];

        let textToParse = (reply || '').replace(/```(?:chartjs|chart|json:chart)\s*([\s\S]*?)```/gi, (match, jsonContent) => {
            try {
                const config = JSON.parse(jsonContent.trim());
                const chartId = 'aiChart_' + Math.random().toString(36).substring(2, 9);
                const title = config.options?.plugins?.title?.text || 'Grafik Analisis Data';
                chartsToRender.push({ id: chartId, config, title });
                return `<div data-chart-slot="${chartId}"></div>`;
            } catch (e) {
                console.error('Gagal parsing Chart.js JSON:', e);
                return match;
            }
        });

        const rawHtml = window.marked ? marked.parse(textToParse) : textToParse;
        const formattedHtml = window.DOMPurify
            ? DOMPurify.sanitize(rawHtml, { ADD_ATTR: ['data-chart-slot'] })
            : rawHtml;

        const bubbleId = 'ai-msg-' + Date.now();

        chatBox.insertAdjacentHTML('beforeend', `
            <div id="${bubbleId}" class="d-flex flex-column align-items-start gap-1 w-100 mb-2">
                <div class="chat-bubble-ai ai-markdown">
                    ${formattedHtml}
                </div>
                <div class="d-flex flex-wrap align-items-center gap-1 px-1 small">
                    <span class="badge bg-secondary-lt font-monospace" style="font-size: 0.65rem;">
                        ⚡ ${escapeHtml(model || '9Router')}
                    </span>
                    ${selfHealed ? `
                        <span class="badge bg-warning-lt" style="font-size: 0.65rem;" title="Kueri berhasil diperbaiki otomatis melalui Auto-Retry Self-Healing">
                            🛡️ Self-Healed
                        </span>` : ''}
                    ${rowCount !== undefined ? `
                        <span class="badge bg-success-lt" style="font-size: 0.65rem;">
                            ${rowCount} baris data
                        </span>` : ''}
                    ${dax ? `
                        <details class="w-100 mt-1">
                            <summary class="text-secondary small fw-medium cursor-pointer" style="font-size: 0.72rem;">Lihat Query DAX</summary>
                            <pre class="mt-1 p-2 bg-dark text-success rounded font-monospace small" style="font-size: 0.7rem; overflow-x: auto;">${escapeHtml(dax)}</pre>
                        </details>` : ''}
                </div>
            </div>
        `);

        const bubbleEl = document.getElementById(bubbleId);
        if (bubbleEl && chartsToRender.length > 0) {
            chartsToRender.forEach((chart) => {
                const slot = bubbleEl.querySelector(`[data-chart-slot="${chart.id}"]`);
                if (slot) {
                    const chartCard = document.createElement('div');
                    chartCard.className = 'card my-2 p-2 border shadow-none bg-body';
                    chartCard.innerHTML = `
                        <div class="d-flex align-items-center justify-content-between pb-1 mb-2 border-bottom">
                            <span class="small fw-bold text-body">
                                📊 ${escapeHtml(chart.title)}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-download-chart py-0 px-2" style="font-size: 0.7rem;">
                                Unduh PNG
                            </button>
                        </div>
                        <div class="position-relative w-100" style="height: 240px;">
                            <canvas id="${chart.id}"></canvas>
                        </div>
                    `;
                    slot.replaceWith(chartCard);

                    chartCard.querySelector('.btn-download-chart')?.addEventListener('click', () => {
                        downloadChart(chart.id, chart.title);
                    });

                    const canvas = document.getElementById(chart.id);
                    if (canvas && window.Chart) {
                        chart.config.options = chart.config.options || {};
                        chart.config.options.responsive = true;
                        chart.config.options.maintainAspectRatio = false;
                        new Chart(canvas.getContext('2d'), chart.config);
                    }
                }
            });
        }

        if (bubbleEl) {
            const tables = bubbleEl.querySelectorAll('.ai-markdown table');
            tables.forEach((table, idx) => {
                const tableId = `table-${Date.now()}-${idx}`;
                table.setAttribute('id', tableId);

                const wrapper = document.createElement('div');
                wrapper.className = 'card my-2 border shadow-none overflow-hidden';

                const toolbar = document.createElement('div');
                toolbar.className = 'card-header py-1 px-2 bg-body-tertiary d-flex align-items-center justify-content-between';
                toolbar.innerHTML = `
                    <span class="small fw-semibold text-secondary" style="font-size: 0.72rem;">
                        📋 Ringkasan Data
                    </span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-sm btn-outline-success btn-excel py-0 px-1" style="font-size: 0.68rem;" title="Unduh Excel">
                            Excel
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-pdf py-0 px-1" style="font-size: 0.68rem;" title="Unduh PDF">
                            PDF
                        </button>
                    </div>
                `;

                toolbar.querySelector('.btn-excel')?.addEventListener('click', () => {
                    exportTableToExcel(table, 'ringkasan-data-powerbi');
                });
                toolbar.querySelector('.btn-pdf')?.addEventListener('click', () => {
                    exportTableToPdf(table, 'ringkasan-data-powerbi');
                });

                const scrollContainer = document.createElement('div');
                scrollContainer.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                scrollContainer.appendChild(table);
                wrapper.appendChild(toolbar);
                wrapper.appendChild(scrollContainer);
            });
        }

        scrollToBottom();
    }

    function appendLoadingBubble() {
        const id = 'loader-' + Date.now();
        chatBox.insertAdjacentHTML('beforeend', `
            <div id="${id}" class="d-flex justify-content-start mb-2">
                <div class="badge bg-body-secondary text-secondary p-2 d-inline-flex align-items-center gap-2 border">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="small">Sedang menganalisis data Power BI...</span>
                </div>
            </div>
        `);
        scrollToBottom();
        return id;
    }

    function appendErrorBubble(errorMsg) {
        chatBox.insertAdjacentHTML('beforeend', `
            <div class="alert alert-danger py-2 px-3 small mb-2" role="alert">
                <strong>Terjadi Kesalahan:</strong> ${escapeHtml(errorMsg)}
            </div>
        `);
        scrollToBottom();
    }

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
</script>
@endpush
