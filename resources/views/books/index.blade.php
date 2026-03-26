@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-zinc-200">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Explore</h1>
            <p class="text-sm text-zinc-500 mt-0.5">
                @if($tab === 'authors')
                    {{ $authors->total() }} {{ $authors->total() === 1 ? 'author' : 'authors' }} available
                @else
                    {{ $books->total() }} {{ $books->total() === 1 ? 'book' : 'books' }} available
                @endif
            </p>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('books.index') }}" class="shrink-0">
            <input type="hidden" name="tab" value="{{ $tab }}">
            @if(request('genre') && $tab === 'books')
                <input type="hidden" name="genre" value="{{ request('genre') }}">
            @endif
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ $tab === 'authors' ? 'Search authors...' : 'Search titles or authors...' }}"
                    class="w-72 h-10 pl-9 pr-4 bg-white border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 placeholder-zinc-400 transition-colors">
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-1 mb-8 bg-zinc-100 rounded-lg p-1 w-fit">
        <a href="{{ route('books.index', array_filter(['search' => request('search')])) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $tab !== 'authors' ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-900' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Books
        </a>
        <a href="{{ route('books.index', array_filter(['tab' => 'authors', 'search' => request('search')])) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $tab === 'authors' ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-900' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Authors
        </a>
    </div>

    @if($tab === 'authors')

        <!-- ── Authors Tab ───────────────────────────────────────────── -->
        @if($authors->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($authors as $author)
                <a href="{{ route('author.profile', $author->id) }}"
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200 rounded-xl hover:border-zinc-300 hover:bg-zinc-50 transition-colors">

                    <!-- Avatar -->
                    <div class="w-14 h-14 rounded-full bg-zinc-900 flex items-center justify-center text-white font-semibold text-xl font-serif shrink-0 overflow-hidden group-hover:bg-zinc-700 transition-colors">
                        @if($author->profile_picture)
                            <img src="{{ Storage::url($author->profile_picture) }}" alt="{{ $author->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($author->name, 0, 1) }}
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-zinc-900 truncate group-hover:text-zinc-600 transition-colors">{{ $author->name }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">
                            {{ $author->books_count }} {{ $author->books_count === 1 ? 'book' : 'books' }}
                        </p>
                        @if($author->bio)
                            <p class="text-xs text-zinc-500 mt-1.5 line-clamp-2 leading-relaxed">{{ $author->bio }}</p>
                        @endif
                    </div>

                    <!-- Arrow -->
                    <svg class="w-4 h-4 text-zinc-300 group-hover:text-zinc-500 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($authors->hasPages())
            <div class="mt-12 pt-6 border-t border-zinc-200">
                {{ $authors->links() }}
            </div>
            @endif

        @else
            <div class="py-20 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-zinc-900">No authors found</p>
                <p class="text-sm text-zinc-400 mt-1">Try adjusting your search.</p>
                @if(request('search'))
                    <a href="{{ route('books.index', ['tab' => 'authors']) }}" class="mt-4 text-sm text-zinc-600 underline underline-offset-2 hover:text-zinc-900 transition-colors">Clear search</a>
                @endif
            </div>
        @endif

    @else

        <!-- ── Books Tab ─────────────────────────────────────────────── -->
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sidebar: Genre Filter -->
            <aside class="w-full lg:w-44 flex-shrink-0">
                <div class="sticky top-24">
                    <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-2 px-3">Genre</p>
                    <nav class="space-y-0.5">
                        <a href="{{ route('books.index', array_filter(['search' => request('search')])) }}"
                           class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('genre') ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                            All Genres
                        </a>
                        @foreach(['Fiction', 'Fantasy', 'Romance', 'Sci-Fi', 'Mystery', 'Horror', 'Non-Fiction', 'Biography'] as $genre)
                            <a href="{{ route('books.index', array_filter(['genre' => strtolower($genre), 'search' => request('search')])) }}"
                               class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request('genre') === strtolower($genre) ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                                {{ $genre }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <!-- Book Grid -->
            <div class="flex-1 min-w-0">
                @if($books->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-x-5 gap-y-8">
                        @foreach($books as $book)
                        <a href="{{ route('books.show', $book) }}" class="group flex flex-col">

                            <!-- Cover -->
                            <div class="relative aspect-[2/3] w-full rounded-lg overflow-hidden bg-zinc-100 mb-3 flex-shrink-0">
                                @if($book->cover_image)
                                    <img src="{{ Storage::url($book->cover_image) }}"
                                         alt="{{ $book->title }}"
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300 ease-out">
                                @else
                                    <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-br from-zinc-600 to-zinc-900">
                                        <div class="absolute inset-y-0 left-0 w-2 bg-black/25"></div>
                                        <div class="p-4">
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-1.5">{{ $book->author->name ?? '' }}</p>
                                            <h3 class="font-serif text-sm font-semibold text-white leading-snug line-clamp-3">{{ $book->title }}</h3>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 rounded-lg ring-1 ring-inset ring-black/10 pointer-events-none"></div>
                            </div>

                            <!-- Meta -->
                            <h2 class="text-sm font-semibold text-zinc-900 group-hover:text-zinc-500 transition-colors line-clamp-1 leading-snug">{{ $book->title }}</h2>
                            <p class="text-xs text-zinc-400 mt-0.5 line-clamp-1">{{ $book->author->name ?? 'Unknown Author' }}</p>
                            @if($book->genre)
                                <span class="mt-2 self-start px-2 py-0.5 bg-zinc-100 text-zinc-500 text-[11px] font-medium rounded">{{ ucfirst($book->genre) }}</span>
                            @endif

                        </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($books->hasPages())
                    <div class="mt-12 pt-6 border-t border-zinc-200">
                        {{ $books->links() }}
                    </div>
                    @endif

                @else
                    <div class="py-20 flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-zinc-900">No books found</p>
                        <p class="text-sm text-zinc-400 mt-1">Try adjusting your search or filter.</p>
                        @if(request('search') || request('genre'))
                            <a href="{{ route('books.index') }}" class="mt-4 text-sm text-zinc-600 underline underline-offset-2 hover:text-zinc-900 transition-colors">Clear all filters</a>
                        @endif
                    </div>
                @endif
            </div>

        </div>

    @endif

</div>
@endsection
