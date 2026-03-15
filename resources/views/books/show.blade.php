@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 animate-fade-in pb-24">

    <!-- Back Navigation -->
    <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 text-zinc-500 hover:text-zinc-900 transition-colors mb-8 group shrink-0 w-fit">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="font-medium tracking-wide">Back to Library</span>
    </a>

    <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 items-start">

        <!-- Left Column: Book Cover -->
        <div class="w-full lg:w-1/3 shrink-0 group relative mx-auto max-w-sm lg:m-0">
            <div class="relative w-full aspect-[2/3] transform transition-transform duration-500 hover:scale-[1.02]">
                <!-- Outer Book Shadow & Edge -->
                <div class="absolute inset-0 bg-black/10 rounded-r-2xl rounded-l-sm shadow-[20px_20px_40px_rgba(0,0,0,0.15)] pointer-events-none"></div>
                <div class="absolute inset-y-0 left-0 w-3 bg-gradient-to-r from-black/40 via-white/20 to-transparent z-20 rounded-l-sm"></div>

                @if($book->cover_image)
                    <img src="{{ Storage::url($book->cover_image) }}" alt="Cover for {{ $book->title }}"
                         class="absolute inset-0 w-full h-full object-cover rounded-r-2xl rounded-l-sm" />
                @else
                    <!-- Fallback Cover Vector -->
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-950 rounded-r-2xl rounded-l-sm flex flex-col items-center justify-center p-8 text-center border border-zinc-700/50" style="box-shadow: inset -2px 0 5px rgba(0,0,0,0.2);">
                        <div class="absolute inset-y-0 left-0 w-4 bg-gradient-to-r from-black/60 to-transparent z-10"></div>
                        <div class="relative z-10 w-16 h-16 mb-4 text-zinc-500">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                               <path d="M21 4H7a2 2 0 00-2 2v12a2 2 0 002 2h14a1 1 0 001-1V5a1 1 0 00-1-1zm-1 13H7V6h13v11zM7 2h14v2H7z"/>
                            </svg>
                        </div>
                        <h3 class="relative z-10 text-3xl font-serif font-black text-white mb-2 drop-shadow-md leading-tight">{{ $book->title }}</h3>
                        <p class="relative z-10 text-xs text-zinc-400 font-bold tracking-[0.2em] uppercase mt-6">{{ $book->author->name ?? 'Unknown Author' }}</p>
                    </div>
                @endif

                <!-- Inner Overlay Highlight -->
                <div class="absolute inset-0 rounded-r-2xl rounded-l-sm ring-1 ring-inset ring-white/10 z-30 pointer-events-none"></div>
            </div>

            <!-- Book actions below cover (Desktop + Mobile) -->
            <div class="mt-8 flex flex-col gap-3">
                <a href="{{ route('books.read', $book) }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-amber-500 text-amber-950 rounded-xl font-bold shadow-md hover:bg-amber-400 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Start Reading
                </a>

                @if($book->downloadable && $book->pdf_path)
                <a href="{{ Storage::url($book->pdf_path) }}" download class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-white border-2 border-zinc-200 text-zinc-700 rounded-xl font-bold hover:border-zinc-300 hover:bg-zinc-50 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download PDF
                </a>
                @endif
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="w-full lg:w-2/3 flex flex-col pt-2 lg:pt-6">

            <!-- Status Badge & Genre -->
            <div class="flex flex-wrap items-center gap-3 mb-6">
                @if($book->status === 'published')
                    <span class="px-3.5 py-1.5 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-bold tracking-wider uppercase shadow-sm border border-emerald-200/50">Available</span>
                @else
                    <span class="px-3.5 py-1.5 bg-amber-100 text-amber-800 rounded-full text-[11px] font-bold tracking-wider uppercase shadow-sm border border-amber-200/50">{{ ucfirst($book->status) }}</span>
                @endif

                @if($book->genre)
                    <span class="px-3.5 py-1.5 bg-zinc-100 text-zinc-700 rounded-full text-[11px] font-bold tracking-wider uppercase shadow-sm border border-zinc-200">{{ $book->genre }}</span>
                @endif
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-[4rem] font-bold text-zinc-900 tracking-tight leading-[1.1] mb-8" style="font-family: ui-serif, Georgia, serif;">
                {{ $book->title }}
            </h1>

            <!-- Enhanced Author Info Card -->
            <div class="mb-12 w-fit">
                <a href="{{ route('author.profile', $book->author->id ?? 1) }}" class="inline-flex items-center gap-4 group p-2 pr-6 rounded-2xl bg-white border border-zinc-100 shadow-sm hover:shadow-md hover:border-zinc-300 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-zinc-800 to-zinc-950 text-white flex items-center justify-center font-serif text-2xl border-[3px] border-white shadow-sm shrink-0 group-hover:rotate-3 transition-transform duration-300">
                        {{ substr($book->author->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black tracking-widest text-zinc-400 uppercase mb-0.5">Written By</span>
                        <span class="text-lg text-zinc-900 font-bold group-hover:text-amber-600 transition-colors leading-none pb-0.5">{{ $book->author->name ?? 'Unknown Author' }}</span>
                    </div>
                    <div class="ml-4 w-8 h-8 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-400 group-hover:bg-amber-50 group-hover:text-amber-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Synopsis / Summary -->
            <div class="prose prose-zinc max-w-none text-zinc-600 leading-relaxed bg-white p-8 sm:p-10 rounded-[2rem] border border-zinc-100 shadow-sm relative overflow-hidden">
                <!-- Decorative Quotes -->
                <div class="absolute top-4 right-6 text-zinc-100 font-serif text-9xl opacity-50 pointer-events-none select-none">"</div>

                <h3 class="text-xs font-black tracking-widest text-zinc-800 uppercase mb-6 border-b border-zinc-100 pb-4 relative z-10 flex items-center gap-3">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                    Synopsis
                </h3>
                <div class="relative z-10 text-[1.1rem]">
                    @if($book->summary)
                        @foreach(explode("\n", $book->summary) as $paragraph)
                            @if(trim($paragraph))
                                <p class="mb-4 last:mb-0">{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    @else
                        <p class="italic text-zinc-400">No synopsis available for this book at the moment.</p>
                    @endif
                </div>
            </div>

            @if($book->created_at)
            <!-- Additional Details Grid -->
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-6 pt-8 border-t border-zinc-100 px-4">
                <div>
                    <span class="block text-[10px] font-black tracking-widest text-zinc-400 uppercase mb-1.5">Published</span>
                    <span class="block text-sm font-semibold text-zinc-800">{{ $book->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-black tracking-widest text-zinc-400 uppercase mb-1.5">Availability</span>
                    <span class="block text-sm font-semibold text-zinc-800">{{ $book->downloadable ? 'Read & Download' : 'Read Online Only' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-black tracking-widest text-zinc-400 uppercase mb-1.5">Format</span>
                    <span class="block text-sm font-semibold text-zinc-800">Digital PDF</span>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
