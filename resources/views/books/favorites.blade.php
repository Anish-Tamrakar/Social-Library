@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-8 font-serif text-gray-900 border-b pb-2">Your Favorites</h1>

<div class="mb-12">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Favorite Books</h2>
    @php $favoriteBooks = $favorites->whereNotNull('book_id'); @endphp

    @if($favoriteBooks->isEmpty())
        <div class="bg-white border border-gray-200 p-8 text-center text-gray-500">
            You haven't favored any books yet.
            <div class="mt-4"><a href="{{ route('books.index') }}" class="text-blue-600 hover:underline">Explore Library</a></div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($favoriteBooks as $favorite)
            <div class="group border border-gray-200 bg-white hover:shadow-lg transition-shadow duration-300 flex flex-col h-full">
                <div class="aspect-w-2 aspect-h-3 bg-gray-100 flex items-center justify-center overflow-hidden">
                    @if($favorite->book->cover_image)
                        <img src="{{ asset('storage/' . $favorite->book->cover_image) }}" alt="{{ $favorite->book->title }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="text-gray-400 font-serif text-lg p-6 text-center group-hover:scale-105 transition-transform duration-300">{{ $favorite->book->title }}</div>
                    @endif
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h2 class="text-lg font-bold text-gray-900 mb-1"><a href="{{ route('books.show', $favorite->book) }}" class="hover:text-blue-600">{{ $favorite->book->title }}</a></h2>
                    <p class="text-gray-600 text-sm">by {{ $favorite->book->author->name }}</p>
                    <div class="mt-auto pt-4">
                        <form action="{{ route('books.favorite', $favorite->book) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<div>
    <h2 class="text-xl font-bold mb-4 text-gray-800">Favorite Authors</h2>
    @php $favoriteAuthors = $favorites->whereNotNull('author_id'); @endphp

    @if($favoriteAuthors->isEmpty())
        <div class="bg-white border border-gray-200 p-8 text-center text-gray-500">
            You haven't favored any authors yet.
        </div>
    @else
        <div class="space-y-4">
            @foreach($favoriteAuthors as $favorite)
            <div class="border border-gray-200 bg-white p-4 flex items-center justify-between hover:shadow-sm transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-200 overflow-hidden">
                        @if($favorite->author->profile_picture)
                            <img src="{{ asset('storage/' . $favorite->author->profile_picture) }}" alt="{{ $favorite->author->name }}" class="object-cover w-full h-full">
                        @else
                            <svg class="w-full h-full text-gray-400 p-2" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><a href="#" class="hover:text-blue-600">{{ $favorite->author->name }}</a></h3>
                        <p class="text-sm text-gray-500">Joined {{ $favorite->author->created_at->format('Y') }}</p>
                    </div>
                </div>
                <!-- Remove author favorite logic not fully implemented yet, but keeping UI ready -->
                <button class="text-sm text-red-600 hover:text-red-800 font-medium border border-red-200 px-3 py-1 bg-white hover:bg-red-50 transition-colors">Unfollow</button>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
