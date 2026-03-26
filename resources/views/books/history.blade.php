@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-8 pb-6 border-b border-zinc-200">
        <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Reading History</h1>
        <p class="text-sm text-zinc-500 mt-0.5">Books you've recently opened or read</p>
    </div>

    <!-- History list -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-semibold text-zinc-900">Books</h2>
            <span class="text-sm text-zinc-400">{{ $history->count() }}</span>
        </div>

        @if($history->isEmpty())
            <div class="py-14 flex flex-col items-center text-center">
                <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-zinc-900">No reading history</p>
                <p class="text-sm text-zinc-400 mt-1">Books you start reading will appear here.</p>
                <a href="{{ route('books.index') }}" class="mt-4 text-sm text-zinc-600 underline underline-offset-2 hover:text-zinc-900 transition-colors">Explore Library</a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-5 gap-y-8">
                @foreach($history as $item)
                <div class="group flex flex-col relative">
                    <!-- Cover -->
                    <a href="{{ route('books.read', $item->book) }}" class="block relative aspect-[2/3] w-full rounded-lg overflow-hidden bg-zinc-100 mb-3 flex-shrink-0">
                        @if($item->book->cover_image)
                            <img src="{{ asset('storage/' . $item->book->cover_image) }}"
                                 alt="{{ $item->book->title }}"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300 ease-out">
                        @else
                            <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-br from-zinc-600 to-zinc-900">
                                <div class="absolute inset-y-0 left-0 w-2 bg-black/25"></div>
                                <div class="p-4">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-1">{{ $item->book->author->name ?? '' }}</p>
                                    <h3 class="font-serif text-sm font-semibold text-white leading-snug line-clamp-3">{{ $item->book->title }}</h3>
                                </div>
                            </div>
                        @endif
                        <div class="absolute inset-0 rounded-lg ring-1 ring-inset ring-black/10 pointer-events-none"></div>

                        <!-- Page indicator overlay -->
                        @if($item->current_page > 0)
                        <div class="absolute bottom-2 right-2 bg-black/70 backdrop-blur-md rounded-full px-2 py-0.5 text-[10px] font-medium text-white/90">
                            Page {{ $item->current_page }}
                        </div>
                        @endif
                    </a>

                    <!-- Meta -->
                    <a href="{{ route('books.show', $item->book) }}" class="text-sm font-semibold text-zinc-900 group-hover:text-zinc-500 transition-colors line-clamp-1 leading-snug">
                        {{ $item->book->title }}
                    </a>
                    <p class="text-xs text-zinc-400 mt-0.5 line-clamp-1">Last opened {{ \Carbon\Carbon::parse($item->opened_at)->diffForHumans() }}</p>

                    <a href="{{ route('books.read', $item->book) }}" class="mt-2.5 inline-flex items-center justify-center gap-1.5 text-xs font-medium text-zinc-900 bg-zinc-100 hover:bg-zinc-200 px-3 py-1.5 rounded transition-colors w-full">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Continue Reading
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
