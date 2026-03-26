@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-20">

    <!-- Back -->
    <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1.5 text-sm text-zinc-400 hover:text-zinc-900 transition-colors mb-10 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Library
    </a>

    <div class="flex flex-col lg:flex-row gap-10 lg:gap-14 items-start">

        <!-- Left: Cover + Actions -->
        <div class="w-full lg:w-64 xl:w-72 shrink-0 mx-auto max-w-xs lg:mx-0">

            <!-- Cover -->
            <div class="relative w-full aspect-[2/3] rounded-lg overflow-hidden shadow-xl">
                @if($book->cover_image)
                    <img src="{{ Storage::url($book->cover_image) }}"
                         alt="{{ $book->title }}"
                         class="absolute inset-0 w-full h-full object-cover" />
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-zinc-700 to-zinc-900 flex flex-col justify-end p-6">
                        <div class="absolute inset-y-0 left-0 w-2.5 bg-black/30"></div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-2">{{ $book->author->name ?? '' }}</p>
                        <h3 class="font-serif text-xl font-bold text-white leading-snug">{{ $book->title }}</h3>
                    </div>
                @endif
                <div class="absolute inset-0 rounded-lg ring-1 ring-inset ring-black/10 pointer-events-none"></div>
            </div>

            <!-- Actions -->
            @auth
                @php $isFavorited = \App\Models\Favorite::where('user_id', auth()->id())->where('book_id', $book->id)->exists(); @endphp
            @endauth
            <div class="mt-5 flex flex-col gap-2.5">
                <a href="{{ route('books.read', $book) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-zinc-900 text-white rounded-lg text-sm font-semibold hover:bg-zinc-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Start Reading
                </a>

                @auth
                <form action="{{ route('books.favorite', $book) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg text-sm font-semibold border transition-colors
                                   {{ $isFavorited
                                       ? 'bg-zinc-50 border-zinc-200 text-zinc-600 hover:border-red-200 hover:text-red-500 hover:bg-red-50'
                                       : 'bg-white border-zinc-200 text-zinc-600 hover:border-zinc-300 hover:bg-zinc-50' }}">
                        <svg class="w-4 h-4" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        {{ $isFavorited ? 'Saved' : 'Save to Library' }}
                    </button>
                </form>
                @endauth

                @if($book->downloadable && $book->pdf_path)
                <a href="{{ Storage::url($book->pdf_path) }}" download
                   class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-white border border-zinc-200 text-zinc-600 rounded-lg text-sm font-semibold hover:bg-zinc-50 hover:border-zinc-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download PDF
                </a>
                @endif
            </div>
        </div>

        <!-- Right: Details -->
        <div class="flex-1 min-w-0 pt-1 lg:pt-2">

            <!-- Status -->
            <div class="mb-5">
                @if($book->status === 'published')
                    <span class="text-xs font-medium text-emerald-700">Available</span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                        {{ ucfirst($book->status) }}
                    </span>
                @endif
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-zinc-900 tracking-tight leading-tight mb-4" style="font-family: ui-serif, Georgia, serif;">
                {{ $book->title }}
            </h1>

            <!-- Author -->
            <a href="{{ route('author.profile', $book->author->id ?? 1) }}"
               class="inline-flex items-center gap-2 text-sm text-zinc-500 hover:text-zinc-900 transition-colors group">
                <div class="w-7 h-7 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-600 font-semibold text-xs shrink-0 overflow-hidden">
                    @if($book->author->profile_picture ?? null)
                        <img src="{{ Storage::url($book->author->profile_picture) }}" alt="{{ $book->author->name }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($book->author->name ?? 'A', 0, 1) }}
                    @endif
                </div>
                <span>{{ $book->author->name ?? 'Unknown Author' }}</span>
                <svg class="w-3.5 h-3.5 text-zinc-300 group-hover:text-zinc-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            @if($book->genre)
            <p class="text-xs text-zinc-400 mt-1.5 mb-10">{{ ucfirst($book->genre) }}</p>
            @else
            <div class="mb-10"></div>
            @endif

            <!-- Synopsis -->
            <div class="mb-8">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-3">Synopsis</p>
                <div class="text-zinc-600 text-base leading-relaxed space-y-3">
                    @if($book->summary)
                        @foreach(explode("\n", $book->summary) as $paragraph)
                            @if(trim($paragraph))
                                <p>{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    @else
                        <p class="italic text-zinc-400">No synopsis available for this book.</p>
                    @endif
                </div>
            </div>

            <!-- Meta -->
            @if($book->created_at)
            <div class="pt-6 border-t border-zinc-100 grid grid-cols-2 sm:grid-cols-3 gap-5">
                <div>
                    <p class="text-xs text-zinc-400 mb-1">Published</p>
                    <p class="text-sm font-medium text-zinc-700">{{ $book->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 mb-1">Availability</p>
                    <p class="text-sm font-medium text-zinc-700">{{ $book->downloadable ? 'Read & Download' : 'Read only' }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 mb-1">Format</p>
                    <p class="text-sm font-medium text-zinc-700">Digital PDF</p>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- ── Reviews ───────────────────────────────────────────────── -->
    <div class="mt-16 pt-10 border-t border-zinc-200">

        <!-- Section header + average -->
        <div class="mb-10">
            <h2 class="text-base font-semibold text-zinc-900 mb-1">Reviews</h2>
            @if($avgRating)
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-zinc-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm font-semibold text-zinc-700">{{ $avgRating }}</span>
                    <span class="text-xs text-zinc-400">· {{ $ratings->count() }} {{ $ratings->count() === 1 ? 'review' : 'reviews' }}</span>
                </div>
            @else
                <p class="text-xs text-zinc-400">No reviews yet</p>
            @endif
        </div>

        @if(session('review_success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-8 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Your review has been saved.
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-12">

            <!-- Write / Edit review (auth, non-author) -->
            @auth
            @if(Auth::id() !== ($book->author_id ?? null))
            <div class="lg:col-span-1">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4">
                    {{ $userRating ? 'Your Review' : 'Write a Review' }}
                </h3>

                <form action="{{ route('books.rate', $book) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Star picker -->
                    <div>
                        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Rating</p>
                        <div class="flex items-center gap-0.5" id="star-picker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" data-star="{{ $i }}"
                                        class="star-btn w-8 h-8 transition-colors focus:outline-none
                                               {{ ($userRating && $i <= $userRating->rating) ? 'text-amber-400' : 'text-zinc-300' }}">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-value" value="{{ $userRating?->rating ?? '' }}">
                        <p id="rating-label" class="text-xs text-zinc-400 mt-1.5">
                            @if($userRating){{ ['','Poor','Fair','Good','Very Good','Excellent'][$userRating->rating] }}@else Click to rate @endif
                        </p>
                    </div>

                    <!-- Review text -->
                    <div>
                        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">
                            Review <span class="normal-case font-normal text-zinc-400">(optional)</span>
                        </p>
                        <textarea name="review" rows="4"
                                  placeholder="What did you think of this book?"
                                  class="w-full px-3 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 resize-none placeholder-zinc-400 transition-colors">{{ $userRating?->review ?? '' }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" id="submit-btn"
                                {{ $userRating ? '' : 'disabled' }}
                                class="h-9 px-4 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                            {{ $userRating ? 'Update' : 'Submit Review' }}
                        </button>

                        @if($userRating)
                        <form action="{{ route('books.review.delete', $book) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Remove your review?')"
                                    class="h-9 px-2 text-sm text-red-500 hover:text-red-600 transition-colors">
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </form>
            </div>
            @endif
            @endauth

            <!-- Review list -->
            <div class="{{ Auth::check() && Auth::id() !== ($book->author_id ?? null) ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                @if($ratings->count() === 0)
                    <div class="py-10 flex flex-col items-center text-center">
                        <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-zinc-900">No reviews yet</p>
                        <p class="text-sm text-zinc-400 mt-1">Be the first to share your thoughts.</p>
                    </div>
                @else
                    <div class="space-y-7">
                        @foreach($ratings as $review)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-zinc-200 overflow-hidden flex items-center justify-center text-zinc-600 font-semibold text-xs shrink-0">
                                @if($review->user->profile_picture)
                                    <img src="{{ Storage::url($review->user->profile_picture) }}" alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($review->user->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="text-sm font-semibold text-zinc-900">{{ $review->user->name }}</span>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-400' : 'text-zinc-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-zinc-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->review)
                                    <p class="text-sm text-zinc-600 leading-relaxed">{{ $review->review }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
    const stars  = document.querySelectorAll('.star-btn');

    function setRating(value) {
        document.getElementById('rating-value').value = value;
        document.getElementById('rating-label').textContent = labels[value];
        document.getElementById('submit-btn').disabled = false;
        highlightStars(value);
    }

    function highlightStars(value) {
        stars.forEach((btn, i) => {
            btn.classList.toggle('text-amber-400', i < value);
            btn.classList.toggle('text-zinc-300',  i >= value);
        });
    }

    // Hover preview
    stars.forEach(btn => {
        btn.addEventListener('mouseenter', () => highlightStars(parseInt(btn.dataset.star)));
        btn.addEventListener('mouseleave', () => highlightStars(parseInt(document.getElementById('rating-value').value) || 0));
    });
</script>
@endpush
