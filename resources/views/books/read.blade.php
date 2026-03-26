@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-50 bg-zinc-950 flex flex-col" id="fullscreen-reader">

    <!-- Top Bar -->
    <div class="flex-none h-14 bg-zinc-950 border-b border-zinc-800 flex items-center justify-between px-4 md:px-6 z-30 gap-4">

        <a href="{{ route('books.show', $book->id) }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-zinc-400 hover:text-white hover:bg-zinc-800 px-3 py-1.5 rounded-lg transition-all flex-shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="hidden sm:inline">Exit Reader</span>
        </a>

        <div class="flex-1 min-w-0 text-center">
            <span class="text-sm font-medium text-zinc-400 truncate font-serif italic">{{ $book->title }}</span>
        </div>

        <div class="flex items-center gap-1 flex-shrink-0">
            @if($book->pdf_path && Storage::disk('public')->exists($book->pdf_path) && $book->pdf_path != 'dummy.pdf')
            <button id="zoom-out-btn" title="Zoom Out (-)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
            <button id="zoom-reset-btn" title="Reset Zoom (0)"
                    class="min-w-[3.5rem] h-8 px-2 flex items-center justify-center rounded-lg text-xs font-semibold text-zinc-400 hover:text-white hover:bg-zinc-800 transition-all tabular-nums">
                <span id="zoom-level">120%</span>
            </button>
            <button id="zoom-in-btn" title="Zoom In (+)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <div class="w-px h-5 bg-zinc-700 mx-1"></div>
            @endif
            <span id="page-info" class="text-sm font-medium text-zinc-500 w-20 text-right tabular-nums">- / -</span>
        </div>
    </div>

    <!-- Reader Container -->
    <div class="flex-1 relative overflow-hidden bg-zinc-900" id="reader-container">

        <div class="absolute inset-0 pointer-events-none opacity-[0.03]"
             style="background-image: repeating-linear-gradient(0deg, transparent, transparent 28px, #71717a 28px, #71717a 29px); background-size: 100% 29px;"></div>

        <!-- Scrollable viewport -->
        <div class="absolute inset-0 overflow-auto" id="reader-viewport">
            <div id="scroll-spacer" class="flex justify-center items-center" style="min-width: 100%; min-height: 100%;">
                <div id="book-wrapper" class="relative z-10" style="opacity: 0; transform: scale(0.96);">
                    @if($book->pdf_path && Storage::disk('public')->exists($book->pdf_path) && $book->pdf_path != 'dummy.pdf')
                        <div id="flipbook"></div>
                    @else
                        <div class="w-96 h-64 bg-white rounded-xl flex flex-col items-center justify-center gap-3 text-zinc-400 border border-zinc-200">
                            <svg class="w-10 h-10 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-base font-semibold text-zinc-700">No PDF available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Loading overlay -->
        <div id="loading" class="absolute inset-0 flex flex-col items-center justify-center bg-zinc-950 z-40 transition-opacity duration-500">
            <svg class="animate-spin w-8 h-8 text-zinc-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span id="loading-text" class="text-sm font-medium text-zinc-400 tracking-wide">Opening book...</span>
            <div class="w-40 h-0.5 bg-zinc-800 rounded-full mt-4 overflow-hidden">
                <div id="loading-progress" class="h-full bg-zinc-500 w-0 transition-all duration-300 rounded-full"></div>
            </div>
        </div>

        <!-- Nav buttons -->
        <button id="prev-page" title="Previous Page (←)"
                class="group absolute left-3 md:left-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-xl bg-zinc-900/60 border border-white/10 text-white flex items-center justify-center hover:bg-zinc-800 transition-all backdrop-blur-sm z-20 disabled:opacity-0 disabled:pointer-events-none">
            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button id="next-page" title="Next Page (→)"
                class="group absolute right-3 md:right-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-xl bg-zinc-900/60 border border-white/10 text-white flex items-center justify-center hover:bg-zinc-800 transition-all backdrop-blur-sm z-20 disabled:opacity-0 disabled:pointer-events-none">
            <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>

@if($book->pdf_path && Storage::disk('public')->exists($book->pdf_path) && $book->pdf_path != 'dummy.pdf')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script src="https://unpkg.com/page-flip@2.0.7/dist/js/page-flip.browser.js"></script>

<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

    // ── State ───────────────────────────────────────────────────────────────

    let pdfDoc     = null;
    let totalPages = 0;
    let pgW        = 0;   // single-page width
    let pgH        = 0;   // page height
    let fitScale   = 1;
    let userZoom   = 1.2;
    let pageFlip   = null;

    const ZOOM_MIN  = 0.4;
    const ZOOM_MAX  = 2.5;
    const ZOOM_STEP = 0.1;

    const url        = @json(asset('storage/' . $book->pdf_path));
    const bookTitle  = @json($book->title);
    const bookAuthor = @json($book->author->name ?? 'Unknown Author');
    const updateProgressUrl = @json(route('books.progress', $book->id));
    const csrfToken = "{{ csrf_token() }}";
    const lastPageIdx = @json($lastPage ?? 0);

    // ── DOM refs ────────────────────────────────────────────────────────────

    const flipbookEl      = document.getElementById('flipbook');
    const bookWrapper     = document.getElementById('book-wrapper');
    const scrollSpacer    = document.getElementById('scroll-spacer');
    const readerContainer = document.getElementById('reader-container');
    const readerViewport  = document.getElementById('reader-viewport');
    const loadingEl       = document.getElementById('loading');
    const prevBtn         = document.getElementById('prev-page');
    const nextBtn         = document.getElementById('next-page');

    // ── Helpers ──────────────────────────────────────────────────────────────

    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function setProgress(n) {
        document.getElementById('loading-progress').style.width = n + '%';
    }

    // ── Zoom ────────────────────────────────────────────────────────────────

    function computeFitScale() {
        const pad = window.innerWidth > 768 ? 100 : 32;
        const sw  = (readerContainer.clientWidth  - pad * 2) / (pgW * 2);
        const sh  = (readerContainer.clientHeight - pad)     / pgH;
        return Math.min(sw, sh, 1.2);
    }

    function applyScale() {
        if (!pgW || !pgH) return;
        fitScale       = computeFitScale();
        const scale    = fitScale * userZoom;
        const bookW    = pgW * 2;
        const effW     = Math.ceil(bookW * scale);
        const effH     = Math.ceil(pgH   * scale);
        const cW       = readerContainer.clientWidth;
        const cH       = readerContainer.clientHeight;
        const pad      = 60;

        scrollSpacer.style.minWidth  = Math.max(effW + pad * 2, cW) + 'px';
        scrollSpacer.style.minHeight = Math.max(effH + pad * 2, cH) + 'px';
        bookWrapper.style.transform  = 'scale(' + scale + ')';
    }

    function resizeBook() { applyScale(); }

    function centerViewport() {
        requestAnimationFrame(function () {
            readerViewport.scrollLeft = (readerViewport.scrollWidth  - readerViewport.clientWidth)  / 2;
            readerViewport.scrollTop  = (readerViewport.scrollHeight - readerViewport.clientHeight) / 2;
        });
    }

    function updateZoomUI() {
        document.getElementById('zoom-level').textContent = Math.round(userZoom * 100) + '%';
        document.getElementById('zoom-out-btn').disabled  = userZoom <= ZOOM_MIN;
        document.getElementById('zoom-in-btn').disabled   = userZoom >= ZOOM_MAX;
    }

    function zoomIn()    { userZoom = Math.min(+(userZoom + ZOOM_STEP).toFixed(2), ZOOM_MAX); applyScale(); centerViewport(); updateZoomUI(); }
    function zoomOut()   { userZoom = Math.max(+(userZoom - ZOOM_STEP).toFixed(2), ZOOM_MIN); applyScale(); centerViewport(); updateZoomUI(); }
    function zoomReset() { userZoom = 1.2; applyScale(); centerViewport(); updateZoomUI(); }

    document.getElementById('zoom-in-btn').addEventListener('click', zoomIn);
    document.getElementById('zoom-out-btn').addEventListener('click', zoomOut);
    document.getElementById('zoom-reset-btn').addEventListener('click', zoomReset);

    readerViewport.addEventListener('wheel', function (e) {
        if (e.ctrlKey || e.metaKey) { e.preventDefault(); e.deltaY < 0 ? zoomIn() : zoomOut(); }
    }, { passive: false });

    // ── PDF page rendering ──────────────────────────────────────────────────

    async function renderPdfPage(pdfIndex, container) {
        if (pdfIndex < 0 || pdfIndex >= totalPages) return;
        if (container.dataset.rendered === 'true' || container.dataset.rendering === 'true') return;
        container.dataset.rendering = 'true';

        try {
            const page  = await pdfDoc.getPage(pdfIndex + 1);
            const baseH = 1200;
            const vp0   = page.getViewport({ scale: 1 });
            const vp    = page.getViewport({ scale: baseH / vp0.height });

            const canvas    = document.createElement('canvas');
            canvas.width    = vp.width;
            canvas.height   = vp.height;
            canvas.style.cssText = 'width:100%;height:100%;display:block;object-fit:contain;';

            await page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise;

            const svg = container.querySelector('svg');
            if (svg) svg.remove();
            container.appendChild(canvas);
            container.dataset.rendered = 'true';
        } catch (err) {
            console.error('Render error page ' + pdfIndex, err);
            container.dataset.rendering = 'false';
        }
    }

    function renderNearby(flipPageIndex) {
        // Pages in flipbook: 0=cover, 1=inner, 2..N+1=pdf, ...
        for (let offset = -3; offset <= 4; offset++) {
            const pdfIdx = (flipPageIndex + offset) - 2;
            if (pdfIdx >= 0 && pdfIdx < totalPages) {
                const el = document.getElementById('pdf-page-' + pdfIdx);
                if (el) renderPdfPage(pdfIdx, el);
            }
        }
    }

    // ── Page info & nav state ───────────────────────────────────────────────

    function updatePageInfo(flipPageIndex) {
        const el    = document.getElementById('page-info');
        const total = pageFlip ? pageFlip.getPageCount() : 0;

        if (flipPageIndex <= 1)           el.textContent = 'Cover';
        else if (flipPageIndex >= total - 2) el.textContent = 'End';
        else {
            const pdfPage = flipPageIndex - 1;
            el.textContent = pdfPage + ' / ' + totalPages;
        }
    }

    function syncNavButtons() {
        if (!pageFlip) return;
        const curr   = pageFlip.getCurrentPageIndex();
        const total  = pageFlip.getPageCount();
        prevBtn.disabled = curr <= 0;
        nextBtn.disabled = curr >= total - 2;
    }

    // ── Init reader ─────────────────────────────────────────────────────────

    async function initReader() {
        try {
            setProgress(20);
            pdfDoc     = await pdfjsLib.getDocument(url).promise;
            totalPages = pdfDoc.numPages;
            setProgress(40);

            // Measure a page
            const p1  = await pdfDoc.getPage(1);
            const vp0 = p1.getViewport({ scale: 1 });
            const s0  = 850 / vp0.height;
            const vp1 = p1.getViewport({ scale: s0 });
            pgW = Math.ceil(vp1.width);
            pgH = Math.ceil(vp1.height);

            setProgress(60);

            // ── Build pages ─────────────────────────────────────────────

            // Front cover
            flipbookEl.innerHTML = ''
                + '<div class="page-item cover-page">'
                +   '<div style="width:100%;height:100%;background:#27272a;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem;text-align:center">'
                +     '<h1 style="font-family:ui-serif,Georgia,serif;font-size:2rem;font-weight:700;color:#fff;margin:0 0 1.5rem;line-height:1.2">' + esc(bookTitle) + '</h1>'
                +     '<div style="width:50px;height:2px;background:#52525b;margin:0 auto 1.5rem"></div>'
                +     '<p style="font-size:0.75rem;color:#a1a1aa;letter-spacing:0.15em;text-transform:uppercase;margin:0">' + esc(bookAuthor) + '</p>'
                +   '</div>'
                + '</div>';

            // Inner front (blank)
            flipbookEl.innerHTML += '<div class="page-item"><div style="width:100%;height:100%;background:#fafaf9"></div></div>';

            // PDF pages
            for (let i = 0; i < totalPages; i++) {
                flipbookEl.innerHTML += ''
                    + '<div class="page-item">'
                    +   '<div id="pdf-page-' + i + '" style="width:100%;height:100%;background:#fff;display:flex;align-items:center;justify-content:center;overflow:hidden">'
                    +     '<svg style="width:24px;height:24px;opacity:0.08" fill="none" viewBox="0 0 24 24" stroke="#a1a1aa"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'
                    +   '</div>'
                    + '</div>';
            }

            // Pad so middle section (between covers) is even
            if ((totalPages + 2) % 2 !== 0) {
                flipbookEl.innerHTML += '<div class="page-item"><div style="width:100%;height:100%;background:#fafaf9"></div></div>';
            }

            // Inner back + back cover
            flipbookEl.innerHTML += '<div class="page-item"><div style="width:100%;height:100%;background:#fafaf9"></div></div>';
            flipbookEl.innerHTML += ''
                + '<div class="page-item cover-page">'
                +   '<div style="width:100%;height:100%;background:#27272a;display:flex;align-items:center;justify-content:center">'
                +     '<div style="width:50px;height:50px;border:2px solid #52525b;border-radius:8px;opacity:0.15"></div>'
                +   '</div>'
                + '</div>';

            setProgress(80);

            // ── PageFlip init ───────────────────────────────────────────

            pageFlip = new St.PageFlip(flipbookEl, {
                width           : pgW,
                height          : pgH,
                size            : 'fixed',
                showCover       : true,
                maxShadowOpacity: 0.4,
                showPageCorners : true,
                mobileScrollSupport: false,
                useMouseEvents  : true,
                flippingTime    : 700,
                startPage       : lastPageIdx,
                drawShadow      : true,
                usePortrait     : false,
                autoSize        : false,
            });

            pageFlip.loadFromHTML(document.querySelectorAll('#flipbook .page-item'));

            pageFlip.on('flip', function (e) {
                updatePageInfo(e.data);
                syncNavButtons();
                renderNearby(e.data);

                // Update progress on server
                if (updateProgressUrl) {
                    fetch(updateProgressUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ page: e.data })
                    }).catch(err => console.error('Error updating progress:', err));
                }
            });

            // Initial setup
            renderNearby(lastPageIdx);
            renderNearby(lastPageIdx + 1);
            renderNearby(lastPageIdx + 2);
            updatePageInfo(lastPageIdx);

            window.addEventListener('resize', resizeBook);
            applyScale();
            centerViewport();
            updateZoomUI();

            setProgress(100);

            // Hide loading
            loadingEl.style.opacity        = '0';
            loadingEl.style.pointerEvents   = 'none';
            setTimeout(function () { loadingEl.style.display = 'none'; }, 500);

            // Reveal
            bookWrapper.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
            bookWrapper.style.opacity    = '1';
            bookWrapper.style.transform  = 'scale(' + (fitScale * userZoom) + ')';
            setTimeout(function () { bookWrapper.style.transition = 'none'; }, 400);

            updatePageInfo(0);
            syncNavButtons();

        } catch (err) {
            console.error(err);
            document.getElementById('loading-text').textContent = 'Failed to load book';
            document.getElementById('loading-text').style.color = '#ef4444';
            document.querySelector('#loading .animate-spin').style.display = 'none';
        }
    }

    // ── Controls ─────────────────────────────────────────────────────────────

    prevBtn.addEventListener('click', function () { if (pageFlip) pageFlip.flipPrev(); });
    nextBtn.addEventListener('click', function () { if (pageFlip) pageFlip.flipNext(); });

    document.addEventListener('keydown', function (e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        switch (e.key) {
            case 'ArrowLeft':  if (pageFlip) pageFlip.flipPrev(); break;
            case 'ArrowRight': if (pageFlip) pageFlip.flipNext(); break;
            case '+': case '=': zoomIn();    break;
            case '-': case '_': zoomOut();   break;
            case '0':           zoomReset(); break;
        }
    });

    // ── Boot ─────────────────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', initReader);
</script>

<style>
    header, footer { display: none !important; }
    body   { background: #09090b !important; overflow: hidden !important; }
    main   { padding: 0 !important; margin: 0 !important; min-height: 100vh; }

    #reader-viewport::-webkit-scrollbar       { width: 6px; height: 6px; }
    #reader-viewport::-webkit-scrollbar-track  { background: transparent; }
    #reader-viewport::-webkit-scrollbar-thumb  { background: #3f3f46; border-radius: 9999px; }

    #flipbook { user-select: none; -webkit-user-select: none; }

    .page-item {
        background: #fafaf9;
        overflow: hidden;
    }
</style>
@endif
@endsection
