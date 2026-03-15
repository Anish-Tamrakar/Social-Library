@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6 pb-8 border-b border-zinc-200">
        <div>
            <h1 class="text-4xl font-bold tracking-tight text-zinc-900 mb-2">Explore the Library</h1>
            <p class="text-zinc-500 font-medium">Discover new worlds, insightful non-fiction, and captivating stories.</p>
        </div>

        <form method="GET" action="{{ route('books.index') }}" class="w-full md:w-auto relative group">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400 group-hover:text-accent-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search titles, authors..."
                class="w-full md:w-80 h-12 pl-12 pr-4 bg-white border border-zinc-200 rounded-full text-sm font-medium focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 shadow-sm transition-all text-zinc-900 placeholder-zinc-400">
        </form>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col lg:flex-row gap-10">

        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white border border-zinc-100 rounded-2xl p-6 shadow-sm sticky top-24">
                <div class="flex items-center gap-2 mb-6 text-zinc-900 font-bold text-sm uppercase tracking-wider">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Categories
                </div>

                <div class="space-y-1">
                    <a href="{{ route('books.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg {{ !request('genre') ? 'bg-zinc-100 text-zinc-900 font-medium' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }} transition-colors text-sm">
                        <span>All Genres</span>
                    </a>
                    @foreach(['Fiction', 'Fantasy', 'Romance', 'Sci-Fi', 'Mystery', 'Horror'] as $g)
                        <a href="{{ route('books.index', ['genre' => strtolower($g)]) }}" class="flex items-center justify-between px-3 py-2 rounded-lg {{ request('genre') == strtolower($g) ? 'bg-accent-50 text-accent-700 font-medium' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }} transition-colors text-sm">
                            <span>{{ $g }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Book Grid -->
        <div class="flex-grow">
            @if(count($books) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($books as $book)
                    <div class="bg-white border border-zinc-100 rounded-2xl p-5 shadow-sm hover:shadow-floating hover:-translate-y-1 transition-all duration-300 group flex flex-col h-full">

                        <!-- Book Cover Mockup -->
                        <div class="aspect-[3/4] w-full bg-zinc-100 rounded-xl mb-5 relative overflow-hidden flex items-center justify-center">
                            <!-- Gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-zinc-800 to-zinc-900 opacity-90 group-hover:scale-105 transition-transform duration-500"></div>

                            <!-- Book spine hint -->
                            <div class="absolute left-0 top-0 bottom-0 w-3 bg-white/10 border-r border-white/5 z-10"></div>

                            <!-- Cover Content -->
                            <div class="relative z-10 p-6 text-center">
                                <h3 class="font-serif font-bold text-white leading-tight mb-2 opacity-90 line-clamp-3 text-lg">{{ $book->title }}</h3>
                                <div class="w-8 h-px bg-accent-400 mx-auto opacity-50"></div>
                            </div>

                            <a href="{{ route('books.show', $book->id) }}" class="absolute inset-0 z-20"></a>
                        </div>

                        <!-- Book Details -->
                        <div class="flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <a href="{{ route('books.show', $book->id) }}" class="font-bold text-zinc-900 text-lg hover:text-accent-600 transition-colors line-clamp-1 truncate" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </a>
                            </div>

                            <p class="text-sm font-medium text-zinc-500 mb-4">{{ $book->author_name }}</p>

                            <div class="mt-auto flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292c.1.306.386.516.71.516h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a.795.795 0 00-.287.886l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a.795.795 0 00-.936 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a.795.795 0 00-.287-.886l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a.794.794 0 00.71-.516l1.07-3.292z"></path></svg>
                                    <span class="text-sm font-bold text-zinc-700">{{ number_format($book->rating, 1) }}</span>
                                </div>

                                <div class="flex gap-2">
                                    @foreach(array_slice(explode(',', $book->genre), 0, 1) as $g)
                                        <span class="px-2.5 py-1 rounded-md bg-zinc-100 text-zinc-600 text-xs font-semibold">{{ trim($g) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-dashed border-zinc-300 rounded-3xl p-16 text-center">
                    <div class="w-16 h-16 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-zinc-100 shadow-sm text-zinc-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">No books found</h3>
                    <p class="text-zinc-500 text-sm">We couldn\'t find any books matching your current filters.</p>
                    @if(request('search') || request('genre'))
                        <a href="{{ route('books.index') }}" class="inline-block mt-6 text-sm font-medium text-accent-600 hover:text-accent-700">Clear all filters</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
