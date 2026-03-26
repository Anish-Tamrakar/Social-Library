@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-8 pb-6 border-b border-zinc-200">
        <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Saved</h1>
        <p class="text-sm text-zinc-500 mt-0.5">Books and authors you've saved</p>
    </div>

    @php
        $favoriteBooks   = $favorites->whereNotNull('book_id');
        $favoriteAuthors = $favorites->whereNotNull('author_id');
    @endphp

    <!-- Saved Books -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-semibold text-zinc-900">Books</h2>
            <span class="text-sm text-zinc-400">{{ $favoriteBooks->count() }}</span>
        </div>

        @if($favoriteBooks->isEmpty())
            <div class="py-14 flex flex-col items-center text-center">
                <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-zinc-900">No saved books</p>
                <p class="text-sm text-zinc-400 mt-1">Books you save will appear here.</p>
                <a href="{{ route('books.index') }}" class="mt-4 text-sm text-zinc-600 underline underline-offset-2 hover:text-zinc-900 transition-colors">Explore Library</a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-5 gap-y-8">
                @foreach($favoriteBooks as $favorite)
                <div class="group flex flex-col">
                    <!-- Cover -->
                    <a href="{{ route('books.show', $favorite->book) }}" class="block relative aspect-[2/3] w-full rounded-lg overflow-hidden bg-zinc-100 mb-3 flex-shrink-0">
                        @if($favorite->book->cover_image)
                            <img src="{{ asset('storage/' . $favorite->book->cover_image) }}"
                                 alt="{{ $favorite->book->title }}"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300 ease-out">
                        @else
                            <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-br from-zinc-600 to-zinc-900">
                                <div class="absolute inset-y-0 left-0 w-2 bg-black/25"></div>
                                <div class="p-4">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-1">{{ $favorite->book->author->name ?? '' }}</p>
                                    <h3 class="font-serif text-sm font-semibold text-white leading-snug line-clamp-3">{{ $favorite->book->title }}</h3>
                                </div>
                            </div>
                        @endif
                        <div class="absolute inset-0 rounded-lg ring-1 ring-inset ring-black/10 pointer-events-none"></div>
                    </a>

                    <!-- Meta -->
                    <a href="{{ route('books.show', $favorite->book) }}"
                       class="text-sm font-semibold text-zinc-900 group-hover:text-zinc-500 transition-colors line-clamp-1 leading-snug">
                        {{ $favorite->book->title }}
                    </a>
                    <p class="text-xs text-zinc-400 mt-0.5 line-clamp-1">{{ $favorite->book->author->name ?? 'Unknown' }}</p>

                    <!-- Remove -->
                    <form action="{{ route('books.favorite', $favorite->book) }}" method="POST" class="mt-2.5">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-red-500 bg-red-50 hover:bg-red-100 border border-red-100 hover:border-red-200 px-2.5 py-1 rounded transition-colors">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Remove
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Saved Authors -->
    @if($favoriteAuthors->isNotEmpty())
    <div>
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-semibold text-zinc-900">Authors</h2>
            <span class="text-sm text-zinc-400">{{ $favoriteAuthors->count() }}</span>
        </div>

        <div class="divide-y divide-zinc-100">
            @foreach($favoriteAuthors as $favorite)
            <div class="flex items-center justify-between py-3">
                <a href="{{ route('author.profile', $favorite->author->id) }}"
                   class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-600 font-semibold text-sm shrink-0 overflow-hidden">
                        @if($favorite->author->profile_picture)
                            <img src="{{ Storage::url($favorite->author->profile_picture) }}" alt="{{ $favorite->author->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($favorite->author->name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900 group-hover:text-zinc-500 transition-colors">{{ $favorite->author->name }}</p>
                        <p class="text-xs text-zinc-400">Joined {{ $favorite->author->created_at->format('Y') }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
