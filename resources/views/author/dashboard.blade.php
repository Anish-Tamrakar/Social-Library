@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 border-b border-zinc-200 pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">Author Hub</h1>
            <p class="text-zinc-500 font-medium text-sm">Manage your publications and monitor engagement.</p>
        </div>
        <button onclick="document.getElementById('addBookModal').classList.remove('hidden')" class="h-11 px-6 rounded-xl bg-zinc-900 text-white text-sm font-medium hover:bg-accent-600 transition-colors shadow-sm flex items-center justify-center gap-2 w-full md:w-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Publish New Work
        </button>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4 text-zinc-500">
                <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="font-semibold text-sm">Published Works</h3>
            </div>
            <p class="text-4xl font-bold tracking-tight text-zinc-900">{{ count($publishedBooks) }}</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4 text-zinc-500">
                <div class="w-10 h-10 rounded-xl bg-accent-50 text-accent-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <h3 class="font-semibold text-sm">Average Rating</h3>
            </div>
            <p class="text-4xl font-bold tracking-tight text-zinc-900">
                {{ count($publishedBooks) > 0 ? number_format(collect($publishedBooks)->avg('rating'), 1) : '0.0' }}
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-zinc-100 to-transparent rounded-full blur-2xl -mr-10 -mb-10"></div>
            <div class="flex items-center gap-4 mb-4 text-zinc-500 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <h3 class="font-semibold text-sm">Status</h3>
            </div>
            <p class="text-xl font-bold tracking-tight text-zinc-900 mt-2 relative z-10">Active Author</p>
        </div>
    </div>

    <!-- Publications Table -->
    <div class="bg-white rounded-3xl border border-zinc-200 overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-zinc-200 flex justify-between items-center bg-zinc-50/50">
            <h2 class="font-bold text-zinc-900">Your Catalog</h2>
        </div>

        @if(count($publishedBooks) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 bg-white">
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Genre</th>
                        <th class="px-6 py-4">Published</th>
                        <th class="px-6 py-4 hidden md:table-cell">Rating</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($publishedBooks as $book)
                    <tr class="hover:bg-zinc-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-10 bg-zinc-200 rounded shadow-inner flex-shrink-0"></div>
                                <div class="font-semibold text-sm text-zinc-900 truncate max-w-[200px]">{{ $book->title }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex text-xs font-medium px-2 py-1 rounded bg-zinc-100 text-zinc-600">
                                {{ explode(',', $book->genre)[0] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500">
                            {{ $book->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-sm font-semibold text-zinc-700">{{ number_format($book->rating, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3 text-sm">
                                <a href="{{ route('books.show', $book->id) }}" class="font-medium text-accent-600 hover:text-accent-700 transition-colors">View</a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-500 hover:text-red-600 transition-colors" onclick="return confirm('Are you sure?')">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-16 text-center">
            <div class="w-16 h-16 bg-zinc-100 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-zinc-200">
                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-zinc-900 mb-2">Publish your first book</h3>
            <p class="text-zinc-500 text-sm mb-6">Share your story with the Social Library community.</p>
            <button onclick="document.getElementById('addBookModal').classList.remove('hidden')" class="inline-block bg-white border border-zinc-200 text-zinc-900 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-zinc-50 transition-colors shadow-sm">Get Started</button>
        </div>
        @endif
    </div>

    <!-- Publishing Modal -->
    <div id="addBookModal" class="fixed inset-0 bg-zinc-900/40 backdrop-blur-[2px] flex items-center justify-center hidden z-50 transition-all">
        <div class="bg-white rounded-3xl w-full max-w-xl mx-4 overflow-hidden shadow-2xl border border-zinc-200 animate-slide-up">
            <div class="px-8 py-6 border-b border-zinc-100 flex justify-between items-center">
                <h3 class="text-xl font-bold tracking-tight text-zinc-900">Publish to Library</h3>
                <button onclick="document.getElementById('addBookModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full bg-zinc-100 text-zinc-500 hover:bg-zinc-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">Title</label>
                    <input type="text" name="title" class="w-full h-11 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900" required>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Author (Pen Name)</label>
                        <input type="text" name="author_name" class="w-full h-11 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Genre(s)</label>
                        <input type="text" name="genre" class="w-full h-11 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900" placeholder="e.g. Sci-Fi, Thriller" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">Synopsis</label>
                    <textarea name="description" rows="4" class="w-full p-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900 resize-none" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">Book PDF File</label>
                    <div class="relative w-full h-11 px-4 bg-zinc-50 border border-zinc-200 rounded-xl flex items-center justify-between overflow-hidden">
                        <input type="file" name="pdf_file" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <span class="text-sm text-zinc-500 pointer-events-none" id="pdf-file-name">Choose a PDF file...</span>
                        <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                </div>

                <script>
                    document.querySelector('input[name="pdf_file"]').addEventListener('change', function(e) {
                        const fileName = e.target.files[0]?.name || 'Choose a PDF file...';
                        document.getElementById('pdf-file-name').textContent = fileName;
                    });
                </script>

                <div class="pt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addBookModal').classList.add('hidden')" class="px-6 h-11 rounded-xl text-sm font-medium text-zinc-600 hover:bg-zinc-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 h-11 bg-zinc-900 text-white rounded-xl text-sm font-medium hover:bg-accent-600 transition-colors shadow-sm">Publish Now</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
