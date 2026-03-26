@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 pb-12">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 font-serif">Manage Books</h1>
            <p class="text-zinc-500 mt-1">Feature books on the homepage and update statuses.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900">&larr; Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-100 overflow-hidden">
        <ul class="divide-y divide-zinc-100">
        @foreach($books as $book)
            <li class="p-6 flex flex-col md:flex-row items-center gap-6">
                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-16 h-20 object-cover rounded-lg shadow-sm">
                <div class="flex-1">
                    <h3 class="text-base font-bold text-zinc-900">{{ $book->title }}</h3>
                    <p class="text-sm text-zinc-500">By {{ $book->author->name }}</p>
                    <div class="mt-2 flex gap-3 text-xs font-medium">
                        @if($book->admin_status === 'approved')
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-md">Approved</span>
                        @elseif($book->admin_status === 'rejected')
                            <span class="px-2 py-1 bg-rose-50 text-rose-700 rounded-md">Rejected</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded-md">Pending</span>
                        @endif

                        @if($book->is_featured)
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-md">Featured</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($book->admin_status === 'approved')
                        <form action="{{ route('admin.books.toggle-featured', $book) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 border {{ $book->is_featured ? 'border-indigo-200 text-indigo-600 hover:bg-indigo-50' : 'border-zinc-200 text-zinc-700 hover:bg-zinc-50' }} rounded-xl text-sm font-medium transition-colors whitespace-nowrap">
                                {{ $book->is_featured ? 'Unfeature' : 'Feature' }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('books.show', $book) }}" class="px-4 py-2 border border-zinc-200 text-zinc-700 rounded-xl text-sm font-medium hover:bg-zinc-50 transition-colors whitespace-nowrap">View</a>
                </div>
            </li>
        @endforeach
        </ul>
    </div>

    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>
@endsection
