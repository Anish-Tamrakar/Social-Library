@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-50 bg-zinc-950 flex flex-col animate-fade-in" id="fullscreen-reader">
    <!-- Top Action Bar -->
    <div class="flex-none h-16 bg-zinc-950 border-b border-zinc-800 flex items-center justify-between px-6 shadow-md z-30">
        <a href="{{ route('books.show', $book->id) }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-400 hover:text-white hover:bg-zinc-800 px-3 py-2 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Exit Reader
        </a>

        <div class="flex items-center gap-4 text-zinc-400 font-medium truncate max-w-md absolute left-1/2 -translate-x-1/2">
            <span class="text-sm truncate font-serif italic tracking-wide"><span class="text-white">{{ $book->title }}</span></span>
        </div>

        <div class="flex items-center gap-3">
            <span id="page-info" class="text-sm font-medium text-zinc-500 w-24 text-right tabular-nums tracking-wider">- / -</span>
        </div>
    </div>

    <!-- Reader Container -->
    <div class="flex-1 relative overflow-hidden flex justify-center items-center bg-zinc-900" id="reader-container">
        <!-- Wood/Desk background subtle effect -->
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #18181b 25%, #18181b 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>

        <!-- Book Wrapper -->
        <div id="book-wrapper" class="relative z-10 transition-transform duration-500 will-change-transform drop-shadow[[0_25px_35px_rgba(0,0,0,0.5)]" style="opacity: 0; transform: scale(0.95) translateY(20px);">
            @if($book->pdf_path && Storage::disk('public')->exists($book->pdf_path) && $book->pdf_path != 'dummy.pdf')
                <div id="flipbook" class="bg-transparent rounded-sm relative"></div>
            @else
                <div class="w-full h-full bg-white flex items-center justify-center rounded-xl p-10">
                    <div class="flex flex-col items-center justify-center text-zinc-500">
                        <svg class="w-16 h-16 text-zinc-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <p class="text-xl font-medium text-zinc-900 mb-2 whitespace-nowrap">No PDF Uploaded</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Loading overlay -->
        <div id="loading" class="absolute inset-0 flex flex-col items-center justify-center bg-zinc-950 transition-opacity duration-700 z-50">
            <div class="relative w-16 h-16 flex items-center justify-center mb-6 text-accent-500">
                <svg class="animate-spin absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path fill="currentColow" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <span id="loading-text" class="font-medium tracking-wide text-zinc-300">Opening Book...</span>
            <div class="w-48 h-1 bg-zinc-800 rounded-full mt-4 overflow-hidden"><div id="loading-progress" class="h-full bg-accent-500 w-0 transition-all duration-300"></div></div>
        </div>

        <button id="prev-page" title="Previous Page (Left Arrow)" class="group absolute left-4 md:left-10 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-zinc-900/50 border border-white/10 text-white flex items-center justify-center hover:bg-zinc-800/80 transition-all backdrop-blur-md z-20 hover:scale-105 shadow-xl disabled:opacity-0 disabled:pointer-events-none">
            <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColow">8path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <button id="next-page" title="Next Page (Right Arrow)" class="group absolute right-4 md:right-10 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-zinc-900/50 border border-white/10 text-white flex items-center justify-center hover:bg-zinc-800/80 transition-all backdrop-blur-md z-20 hover:scale-105 shadow-xl disabled:opacity-0 disabled:pointer-events-none">
            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
</div>

@if($book->pdf_path && Storage::disk('public')->exists($book->pdf_path) && $book->pdf_path != 'dummy.pdf')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/3/turn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

    let pdfDoc = null;
    let totalPages = 0;
    let totalFlipPages = 0;
    let bookWidth = 0;
    let bookHeight = 0;

    const url = @json(asset('storage/' . $book->pdf_path));
    const bookTitle = @json($book->title);
    const bookAuthor = @json($book->author_name ?? $book->author->name ?? 'Unknown Author');

    const flipbook = $('#flipbook');
    const bookWrapper = $('#book-wrapper');
    const loading = $('#loading');
    const prevBtn = $('#prev-page');
    const nextBtn = $('#next-page');

    async function renderPage(flipbookPageNum) {
        const pdfPageNum = flipbookPageNum - 2;

        if (pdfPageNum < 1 || pdfPageNum > totalPages) return;

        const div = document.getElementById('page-container-' + pdfPageNum);
        if (!div) return;

        if (div.dataset.rendered === 'true' || div.dataset.rendering === 'true') return;
        div.dataset.rendering = 'true';

        try {
            const page = await pdfDoc.getPage(pdfPageNum);
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            const viewportUnscaled = page.getViewport({ scale: 1 });
            const baseHeight = 1200;
            const scale = baseHeight / viewportUnscaled.height;
            const viewport = page.getViewport({ scale: scale });

            canvas.height = viewport.height;
            canvas.width = viewport.width;
            canvas.classList.add('w-full', 'h-full', 'object-contain');

            await page.render({ canvasContext: ctx, viewport: viewport }).promise;

            let s = div.querySelector('svg'); if(s) s.remove();
            div.appendChild(canvas);
            div.dataset.rendered = 'true';
        } catch (err) {
            console.error('Error rendering page ' , pdfPageNum, err);
            div.innerHTML = `<div class="text-red-500 font-medium bg-red-50 p-4 rounded-xl">Error loading page ${pdfPageNum}</div>`;
            div.dataset.rendering = 'false';
        }
    }

    function resizeBook() {
        if (!bookWidth || !bookHeight) return;
        const container = $('#reader-container');
        const padding = $(window).width() > 768 ? 120 : 40;

        const availableWidth = container.width() - padding * 2;
        const availableHeight = container.height() - padding;

        const scaleW = availableWidth / bookWidth;
        const scaleH = availableHeight / bookHeight;
        const scale = Math.min(scaleW, scaleH, 1.2);

        bookWrapper.css('transform', `scale(${scale}) translateY(0)`);
    }

    async function initReader() {
        try {
            $('#loading-progress').css('width', '20%');
            pdfDoc = await pdfjsLib.getDocument(url).promise;
            totalPages = pdfDoc.numPages;
            $('#loading-progress').css('width', '40%');

            const page1 = await pdfDoc.getPage(1);
            const viewport = page1.getViewport({ scale: 850 / page1.getViewport({ scale: 1 }).height });
            bookHeight = viewport.height;
            bookWidth = viewport.width * 2;

            $('#loading-progress').css('width', '60%');

            let htmlBuffer = `
                <div class="hard cover-page bg-zinc-800 flex flex-col items-center justify-center p-12 text-center border-r border-zinc-700 shadow-[-10px_0_20px_rgba(0,0,0,0.3)_inset]">
                    <div class="flex-1 flex flex-col justify-center">
                        <h1 class="text-3xl md:text-5xl font-serif font-bold text-white mb-6 leading-tight">${bookTitle}</h1>
                        <p class="text-xl text-zinc-400 font-medium tracking-wide border-t border-zinc-600 pt-6 mt-2 inline-block mx-auto min-w-[50%]">${bookAuthor}</p>
                    </div>
                </div>
            `;

            htmlBuffer += `<div class="hard bg-[#fcfcfa] shadow-[10px_0_20px_rgba(0,0,0,0.05)_inset]"></div>`;

            // Document Pages
            for (let i = 1; i <= totalPages; i++) {
                const flipbookPageNum = i + 2;
                let shadowClass = '';
                if (flipbookPageNum % 2 === 0) {
                    shadowClass = 'right-0 bg-gradient-to-l from-black/20 to-transparent';
                } else {
                    shadowClass = 'left-0 bg-gradient-to-r from-black/20 to-transparent';
                }

                htmlBuffer += `
                    <div class="page-wrapper bg-[#fcfcfa]">
                        <div id="page-container-${i}" class="w-full h-full flex flex-col items-center justify-center relative bg-[#ffffff] text-zinc-400 overflow-hidden">
                            <div class="absolute inset-y-0 w-16 pointer-events-none z-10 mix-blend-multiply opacity-50 ${shadowClass}"></div>
                            <svg class="w-8 h-8 opacity-20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>
                `;
            }

            let addedPad = false;
            if (totalPages % 2 !== 0) {
                htmlBuffer += `
                    <div class="page-wrapper bg-[#fcfcfa]">
                        <div class="w-full h-full relative bg-[#ffffff]">
                            <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-black/20 to-transparent pointer-events-none z-10 mix-blend-multiply opacity-50"></div>
                        </div>
                    </div>
                `;
                addedPad = true;
            }

            htmlBuffer += `<div class="hard bg-[#fcfcfa] shadow-[-10px_0_20px_rgba(0,0,0,0.05)_inset]"></div>`;
            htmlBuffer += `<div class="hard cover-page bg-zinc-800 border-l border-zinc-700 shadow-[10px_0_20px_rgba(0,0,0,0.3)_inset] flex items-center justify-center"><div class="w-32 h-32 opacity-10 bg-zinc-500 rounded-full flex items-center justify-center"><div class="w-24 h-24 bg-zinc-800 rounded-full border-4 border-zinc-500"></div></div></div>`;

            flipbook.html(htmlBuffer);
            totalFlipPages = totalPages + 4 + (addedPad ? 1 : 0);

            $('#loading-progress').css('width', '80%');

            flipbook.turn({
                width: bookWidth,
                height: bookHeight,
                autoCenter: false,
                display: 'double',
                acceleration: true,
                elevation: 0,
                gradients: true,
                pages: totalFlipPages,
                when: {
                    turning: function(event, page, view) {
                        updateProgressInfo(page);
                        updateButtons(view[0], view[1]);
                        view.forEach(p => p && renderPage(p));
                    },
                    turned: function(event, page, view) {
                        view.forEach(p => p && renderPage(p));
                        setTimeout(() => {
                            if(view[0]) {
                                renderPage(view[0] - 1); renderPage(view[0] - 2);
                                renderPage(view[0] - 3); renderPage(view[0] - 4);
                            }
                            if(view[1]) {
                                renderPage(view[1] + 1); renderPage(view[1] + 2);
                                renderPage(view[1] + 3); renderPage(view[1] + 4);
                            }
                        }, 50);
                    }
                }
            });

            $(window).on('resize', resizeBook);
            resizeBook();

            $('#loading-progress').css('width', '100%');

            Promise.all([renderPage(3), renderPage(4)]);

            loading.css('opacity', '0').css('pointer-events', 'none');
            setTimeout(() => loading.hide(), 400);

            bookWrapper.css('opacity', '1');
          updateProgressInfo(1);
            updateButtons(1, 0);

        } catch (err) {
            console.error(err);
            $('#loading-text').text('Failed to load book').addClass('text-red-500');
            $('#loading .animate-spin').hide();
        }
    }

    function updateProgressInfo(flipbookPage) {
        let realPage = Math.max(1, flipbookPage - 2);
        if (realPage > totalPages) realPage = totalPages;

        if (flipbookPage === 1 || flipbookPage === 2) $('#page-info').text(`Cover`);
        else if (flipbookPage >= totalFlipPages - 1) $('#page-info').text(`End`);
        else $('#page-info').text(`${realPage} / ${totalPages}`);
    }

    function updateButtons(leftPage, rightPage) {
        if(leftPage === 1 || rightPage === 1 || (leftPage === 0 && rightPage === 1)) {
            prevBtn.prop('disabled', true);
        } else {
            prevBtn.prop('disabled', false);
        }

        if(leftPage === totalFlipPages || rightPage === totalFlipPages) {
            nextBtn.prop('disabled', true);
        } else {
            nextBtn.prop('disabled', false);
        }
    }

    prevBtn.click(() => flipbook.turn('previous'));
    nextBtn.click(() => flipbook.turn('next'));

    $(document).keydown(function(e){
        if (e.keyCode === 37) flipbook.turn('previous');
        if (e.keyCode === 39) flipbook.turn('next');
    });

    $(document).ready(() => {
        initReader();
    });
</script>

<style>
    header, footer { display: none !important; }
    body { background-color: #09090b !important; overflow: hidden !important; }
    #app { display: block !important; padding: 0 !important; }
    main { padding: 0 !important; margin: 0 !important; min-height: 100vh; }

    .page-wrapper {
        border-radius: 2px;
    }

    .hard {
        border-radius: 4px;
    }

    .page-wrapper:not(.cover-page)::after, .hard:not(.cover-page)::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml;utf8,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%5D");
        pointer-events: none;
        z-index: 50;
        mix-blend-mode: multiply;
    }
</style>
@endif
@endsection
