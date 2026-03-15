@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">

    <!-- Profile Header -->
    <div class="bg-white rounded-3xl p-8 md:p-12 mb-12 flex flex-col md:flex-row items-center gap-8 border border-zinc-200 shadow-sm relative overflow-hidden">
        <!-- Background subtle glow -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-zinc-50 rounded-full opacity-60 -mr-20 -mt-20"></div>

        <div class="relative z-10 w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white shadow-md bg-zinc-100 flex items-center justify-center text-zinc-700 text-4xl font-serif font-bold shrink-0">
            {{ substr($user->name, 0, 1) }}
        </div>

        <div class="relative z-10 text-center md:text-left flex-grow">
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-zinc-900 mb-2">{{ $user->name }}</h1>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-sm text-zinc-500 font-medium mb-4">
                <span class="px-2.5 py-1 bg-zinc-100 text-zinc-700 rounded-md capitalize font-semibold">{{ $user->role }}</span>
                <span>�</span>
                <span>Member since {{ $user->created_at->format('M Y') }}</span>
            </div>
            @if($user->bio)
                <p class="text-zinc-600 max-w-2xl leading-relaxed">{{ $user->bio }}</p>
            @else
                <p class="text-zinc-400 italic">No biography provided.</p>
            @endif
        </div>
    </div>

    <!-- User's Library -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Collection by {{ $user->name }}</h2>
        <div class="text-sm font-medium text-zinc-500 bg-zinc-100 px-3 py-1 rounded-full">
            {{ count($books) }} Books
        </div>
    </div>

    @if(count($books) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
            <a href="{{ route('books.show', $book->id) }}" class="group block">
                <div class="bg-white rounded-2xl p-5 border border-zinc-200 shadow-sm hover:shadow-floating hover:-translate-y-1 transition-all duration-300 flex items-start gap-5 h-full">

                   <!-- Mini Cover -->
                   <div class="w-16 h-24 bg-gradient-to-br from-zinc-800 to-zinc-900 rounded-lg shadow-sm flex-shrink-0 relative overflow-hidden flex items-center justify-center">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-white/10 border-r border-white/5"></div>
                        <span class="text-[10px] font-serif font-bold text-white opacity-90 px-1 text-center truncate w-full">{{ $book->title }}</span>
                   </div>

                   <div class="flex flex-col justify-center h-full flex-grow py-1 min-w-0">
                       <h3 class="font-bold text-zinc-900 leading-tight mb-1 group-hover:text-accent-600 transition-colors truncate">{{ $book->title }}</h3>
                       <p class="text-sm text-zinc-500 mb-3 truncate">{{ $book->author->name ?? 'Unknown Author' }}</p>

                       <div class="flex items-center justify-between mt-auto">
                           <div class="flex items-center gap-1">
                               <svg class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                               <span class="text-xs font-bold text-zinc-700">{{ number_format($book->ratings()->avg('rating') ?? 0, 1) }}</span>
                           </div>
                           <span class="text-[10px] font-semibold bg-zinc-100 text-zinc-600 px-2 py-0.5 rounded">{{ explode(',', $book->genre)[0] }}</span>
                       </div>
                   </div>
                </div>
            </a>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-dashed border-zinc-200 rounded-3xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-zinc-400 border border-zinc-100">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h3 class="text-lg font-bold tracking-tight text-zinc-900 mb-2">No books published yet</h3>
            <p class="text-zinc-500 text-sm mb-6 max-w-sm mx-auto">This author currently has no published books in their collection.</p>
            <a href="{{ route('books.index') }}" class="inline-block bg-white text-zinc-900 border border-zinc-200 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-zinc-50 hover:border-zinc-300 transition-colors shadow-sm">Explore Books</a>
        </div>
    @endif
</div>
@endsection
