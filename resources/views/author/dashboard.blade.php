@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10 pb-8 border-b border-zinc-200">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Author Dashboard</h1>
            <p class="text-sm text-zinc-500 mt-0.5">Manage your publications.</p>
        </div>
        <button onclick="document.getElementById('addBookModal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 h-10 px-5 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Publish New Work
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-10">
        <div class="border border-zinc-200 rounded-lg p-5">
            <p class="text-xs text-zinc-400 mb-2">Published Works</p>
            <p class="text-3xl font-bold text-zinc-900">{{ count($publishedBooks) }}</p>
        </div>
        <div class="border border-zinc-200 rounded-lg p-5">
            <p class="text-xs text-zinc-400 mb-2">Total Readers</p>
            <p class="text-3xl font-bold text-zinc-900">—</p>
        </div>
        <div class="border border-zinc-200 rounded-lg p-5 col-span-2 sm:col-span-1">
            <p class="text-xs text-zinc-400 mb-2">Status</p>
            <p class="text-base font-semibold text-emerald-700">Active</p>
        </div>
    </div>

    <!-- Publications Table -->
    <div class="border border-zinc-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-200 bg-zinc-50/50">
            <h2 class="text-sm font-semibold text-zinc-900">Your Publications</h2>
        </div>

        @if(count($publishedBooks) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider border-b border-zinc-200">
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Genre</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($publishedBooks as $book)
                    <tr class="hover:bg-zinc-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-9 bg-zinc-200 rounded flex-shrink-0"></div>
                                <span class="text-sm font-medium text-zinc-900 truncate max-w-[200px]">{{ $book->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($book->genre)
                                <span class="text-xs font-medium px-2 py-0.5 rounded bg-zinc-100 text-zinc-500">
                                    {{ ucfirst(explode(',', $book->genre)[0]) }}
                                </span>
                            @else
                                <span class="text-xs text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($book->admin_status === 'approved')
                                <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded">Live</span>
                            @elseif($book->admin_status === 'pending')
                                <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded">Reviewing</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500">
                            {{ $book->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4 text-sm">
                                <a href="{{ route('books.show', $book->id) }}"
                                   class="font-medium text-zinc-600 hover:text-zinc-900 transition-colors">View</a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="font-medium text-red-500 hover:text-red-600 transition-colors"
                                            onclick="return confirm('Remove this book permanently?')">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-20 flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-zinc-900">No books published yet</p>
            <p class="text-sm text-zinc-400 mt-1">Share your first story with the community.</p>
            <button onclick="document.getElementById('addBookModal').classList.remove('hidden')"
                    class="mt-4 text-sm text-zinc-600 underline underline-offset-2 hover:text-zinc-900 transition-colors">
                Publish a Book
            </button>
        </div>
        @endif
    </div>

</div>

<!-- Publish Modal -->
<div id="addBookModal" class="fixed inset-0 bg-zinc-900/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-xl mx-4 overflow-hidden border border-zinc-200 shadow-xl">
        <div class="px-6 py-5 border-b border-zinc-200 flex justify-between items-center">
            <h3 class="text-base font-semibold text-zinc-900">Publish to Library</h3>
            <button onclick="document.getElementById('addBookModal').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 hover:bg-zinc-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Title</label>
                <input type="text" name="title"
                       class="w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors"
                       required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Pen Name</label>
                    <input type="text" name="author_name"
                           class="w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors"
                           value="{{ auth()->user()->name }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Genre</label>
                    <input type="text" name="genre"
                           class="w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors"
                           placeholder="e.g. Fiction, Thriller" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Synopsis</label>
                <textarea name="description" rows="4"
                          class="w-full p-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 resize-none transition-colors"
                          required></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">PDF File</label>
                <div class="relative w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg flex items-center justify-between overflow-hidden">
                    <input type="file" name="pdf_file" accept="application/pdf"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                    <span class="text-sm text-zinc-400 pointer-events-none" id="pdf-file-name">Choose a PDF file...</span>
                    <svg class="w-4 h-4 text-zinc-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
            </div>

            <script>
                document.querySelector('input[name="pdf_file"]').addEventListener('change', function(e) {
                    document.getElementById('pdf-file-name').textContent = e.target.files[0]?.name || 'Choose a PDF file...';
                });
            </script>

            <div class="pt-2 flex justify-end gap-3">
                <button type="button"
                        onclick="document.getElementById('addBookModal').classList.add('hidden')"
                        class="px-4 h-10 rounded-lg text-sm font-medium text-zinc-600 hover:bg-zinc-100 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 h-10 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-700 transition-colors">
                    Publish Now
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

