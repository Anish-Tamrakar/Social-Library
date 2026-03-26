@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 pb-12">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 font-serif">Flagged Reviews</h1>
            <p class="text-zinc-500 mt-1">Review and manage reported user reviews.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900">&larr; Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-100 overflow-hidden">
        @if($ratings->isEmpty())
            <div class="p-8 text-center text-zinc-500">
                No flagged reviews at the moment.
            </div>
        @else
            <ul class="divide-y divide-zinc-100">
            @foreach($ratings as $rating)
                <li class="p-6 flex flex-col md:flex-row items-start md:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-sm font-bold text-zinc-900">{{ $rating->user->name ?? 'Unknown user' }}</h3>
                            <span class="text-xs text-zinc-500">on <a href="{{ route('books.show', $rating->book) }}" class="text-indigo-600 hover:underline">{{ $rating->book->title }}</a></span>
                        </div>
                        <div class="text-amber-400 text-sm mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $rating->rating ? 'opacity-100' : 'opacity-30' }}">★</span>
                            @endfor
                            <span class="text-zinc-500 ml-1">({{ $rating->rating }}/5)</span>
                        </div>
                        <p class="text-sm text-zinc-700 italic border-l-2 border-zinc-200 pl-3">"{{ $rating->review }}"</p>
                    </div>
                    <div class="flex items-center gap-3 mt-4 md:mt-0">
                        <form action="{{ route('admin.ratings.unflag', $rating) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-zinc-100 text-zinc-700 rounded-xl text-sm font-medium hover:bg-zinc-200 transition-colors whitespace-nowrap">Keep (Unflag)</button>
                        </form>

                        <form action="{{ route('admin.ratings.delete', $rating) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-xl text-sm font-medium hover:bg-rose-700 transition-colors whitespace-nowrap">Delete Review</button>
                        </form>
                    </div>
                </li>
            @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-6">
        {{ $ratings->links() }}
    </div>
</div>
@endsection
