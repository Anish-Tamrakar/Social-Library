@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 pb-12">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 font-serif">Pending Books</h1>
            <p class="text-zinc-500 mt-1">Review books submitted by authors.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900">&larr; Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-100 overflow-hidden">
        @if($books->isEmpty())
            <div class="p-8 text-center text-zinc-500">
                No pending books at the moment.
            </div>
        @else
            <ul class="divide-y divide-zinc-100">
            @foreach($books as $book)
                <li class="p-6 flex flex-col md:flex-row items-center gap-6">
                    <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-24 h-32 object-cover rounded-lg shadow-sm">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-zinc-900">{{ $book->title }}</h3>
                        <p class="text-sm text-zinc-500 mt-1">By {{ $book->author->name }}</p>
                        <p class="text-sm text-zinc-600 mt-2 line-clamp-2">{{ $book->summary }}</p>
                        <div class="mt-2 flex gap-4 text-xs font-medium text-zinc-400">
                            <span>Genre: {{ $book->genre }}</span>
                            <span>Submitted: {{ $book->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ Storage::url($book->pdf_path) }}" target="_blank" class="px-4 py-2 border border-zinc-200 text-zinc-700 rounded-xl text-sm font-medium hover:bg-zinc-50 transition-colors whitespace-nowrap">View PDF</a>

                        <form action="{{ route('admin.books.approve', $book) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors whitespace-nowrap">Approve</button>
                        </form>

                        <form action="{{ route('admin.books.reject', $book) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 border border-rose-200 text-rose-600 rounded-xl text-sm font-medium hover:bg-rose-50 transition-colors whitespace-nowrap">Reject</button>
                        </form>
                    </div>
                </li>
            @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>
@endsection
