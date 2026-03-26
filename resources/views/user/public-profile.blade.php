@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Profile Header -->
    <div class="flex flex-col sm:flex-row items-start gap-6 mb-12 pb-10 border-b border-zinc-200">
        <div class="w-16 h-16 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-700 text-2xl font-serif font-semibold shrink-0 overflow-hidden">
            @if($user->profile_picture)
                <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
            @else
                {{ substr($user->name, 0, 1) }}
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-3 mb-1">
                <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">{{ $user->name }}</h1>
                <span class="text-xs font-medium text-zinc-400 capitalize">{{ $user->role }}</span>
            </div>
            <p class="text-xs text-zinc-400 mb-3">Member since {{ $user->created_at->format('M Y') }}</p>
            @if($user->bio)
                <p class="text-sm text-zinc-600 leading-relaxed max-w-xl">{{ $user->bio }}</p>
            @else
                <p class="text-sm text-zinc-400 italic">No biography provided.</p>
            @endif
        </div>
    </div>

    <!-- Books Section -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-base font-semibold text-zinc-900">Books by {{ $user->name }}</h2>
        <span class="text-sm text-zinc-400">{{ count($books) }} {{ count($books) === 1 ? 'book' : 'books' }}</span>
    </div>

    @if(count($books) > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-8">
            @foreach($books as $book)
            <a href="{{ route('books.show', $book->id) }}" class="group flex flex-col">

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
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-1">{{ $user->name }}</p>
                                <h3 class="font-serif text-sm font-semibold text-white leading-snug line-clamp-3">{{ $book->title }}</h3>
                            </div>
                        </div>
                    @endif
                    <div class="absolute inset-0 rounded-lg ring-1 ring-inset ring-black/10 pointer-events-none"></div>
                </div>

                <!-- Meta -->
                <h3 class="text-sm font-semibold text-zinc-900 group-hover:text-zinc-500 transition-colors line-clamp-1 leading-snug">{{ $book->title }}</h3>
                @if($book->genre)
                    <span class="mt-1.5 self-start px-2 py-0.5 bg-zinc-100 text-zinc-500 text-[11px] font-medium rounded">{{ ucfirst($book->genre) }}</span>
                @endif

            </a>
            @endforeach
        </div>

    @else
        <div class="py-20 flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-zinc-900">No books published yet</p>
            <p class="text-sm text-zinc-400 mt-1">This author hasn't published any books yet.</p>
            <a href="{{ route('books.index') }}" class="mt-4 text-sm text-zinc-600 underline underline-offset-2 hover:text-zinc-900 transition-colors">Explore Library</a>
        </div>
    @endif

</div>
@endsection
