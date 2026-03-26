@extends('layouts.app')

@push('styles')
<style>
    /* Keyframes for Animations */
    @keyframes slide-up-fade {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Custom Animation Classes */
    .animate-slide-up-fade {
        animation: slide-up-fade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-spin-slow { animation: spin 12s linear infinite; }

    /* Staggered animation delays */
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    .delay-500 { animation-delay: 500ms; }

    /* Glass Panels */
    .glass-panel {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
    }
    .glass-dark {
        background: rgba(24, 24, 27, 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Gradient Text & Magic Elements */
    .text-gradient {
        background: linear-gradient(135deg, #000000 0%, #71717a 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .bg-gradient-mesh {
        background: radial-gradient(circle at 10% 20%, rgba(200, 200, 200, 0.05) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(150, 150, 150, 0.05) 0%, transparent 40%);
    }

    /* Patterns */
    .pattern-dots {
        background-image: radial-gradient(rgba(0, 0, 0, 0.1) 1px, transparent 1px);
        background-size: 20px 20px;
    }

    /* Scrollbar hide for horizontal scroller */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative min-h-[90vh] flex flex-col items-center justify-center bg-gradient-mesh overflow-hidden -mt-16 pt-16">
    <!-- Floating Background Orbs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-zinc-200/40 rounded-xl mix-blend-multiply filter blur-3xl opacity-70 animate-float"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-zinc-300/40 rounded-xl mix-blend-multiply filter blur-3xl opacity-70 animate-float delay-200"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center flex flex-col items-center">
        <!-- Badge -->
        <a href="#features" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/60 backdrop-blur-md border border-zinc-200 text-sm font-medium text-zinc-700 mb-8 shadow-xl hover:bg-white/80 transition-all cursor-pointer animate-slide-up-fade">
            Meet the New Social Library
        </a>

        <!-- Headline -->
        <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-6 text-balance max-w-5xl mx-auto animate-slide-up-fade delay-100 text-zinc-900 leading-[1.1]">
            Where great stories <br/> find their <span class="text-gradient">community.</span>
        </h1>

        <!-- Subheadline -->
        <p class="text-xl md:text-2xl text-zinc-600 max-w-2xl mx-auto mb-12 font-medium animate-slide-up-fade delay-200 leading-relaxed">
            Read, review, and share books in a beautifully designed space built for modern readers and passionate writers.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4 animate-slide-up-fade delay-300 w-full sm:w-auto">
            <a href="{{ route('books.index') }}" class="w-full sm:w-auto h-14 px-8 rounded-xl bg-zinc-900 text-white font-semibold flex items-center justify-center hover:bg-zinc-800 hover:shadow-xl  active:scale-95 transition-all duration-200 group">
                Start Exploring
                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
            @guest
            <a href="{{ route('register') }}" class="w-full sm:w-auto h-14 px-8 rounded-xl bg-white text-zinc-900 font-semibold flex items-center justify-center border border-zinc-200 shadow-xl hover:border-zinc-300 hover:bg-zinc-50  active:scale-95 transition-all duration-200">
                Join the Network
            </a>
            @endguest
        </div>
    </div>
</div>

<!-- Features Section (Differentiable background) -->
<div id="features" class="py-32 bg-zinc-50 pattern-dots relative border-y border-zinc-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16 animate-slide-up-fade">
            <h2 class="text-sm font-bold tracking-widest text-accent-600 uppercase mb-3">Why Social Library</h2>
            <h3 class="text-3xl md:text-5xl font-black text-zinc-900 tracking-tight">Everything you need to <br />elevate your reading.</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature 1: Reader -->
            <div class="bg-white rounded-xl p-8 shadow-xl border border-zinc-200 flex flex-col hover:shadow-xl transition-shadow group">
                <div class="w-14 h-14 rounded-xl bg-zinc-100 text-zinc-900 flex items-center justify-center mb-8 group-hover:bg-zinc-900 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h4 class="text-2xl font-bold text-zinc-900 mb-3">Immersive Reading</h4>
                <p class="text-zinc-600 font-medium leading-relaxed">Dive deep into stories with our distraction-free, customizable reading interface. Night mode included.</p>
            </div>

            <!-- Feature 2: Curate -->
            <div class="bg-white rounded-xl p-8 shadow-xl border border-zinc-200 flex flex-col hover:shadow-xl transition-shadow group">
                <div class="w-14 h-14 rounded-xl bg-zinc-100 text-zinc-900 flex items-center justify-center mb-8 group-hover:bg-zinc-900 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                </div>
                <h4 class="text-2xl font-bold text-zinc-900 mb-3">Curate Lists</h4>
                <p class="text-zinc-600 font-medium leading-relaxed">Save your favorites, track your reading history, and organize books exactly how you want.</p>
            </div>

            <!-- Feature 3: Social -->
            <div class="bg-white rounded-xl p-8 shadow-xl border border-zinc-200 flex flex-col hover:shadow-xl transition-shadow group">
                <div class="w-14 h-14 rounded-xl bg-zinc-100 text-zinc-900 flex items-center justify-center mb-8 group-hover:bg-zinc-900 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h4 class="text-2xl font-bold text-zinc-900 mb-3">Engage & Publish</h4>
                <p class="text-zinc-600 font-medium leading-relaxed">Connect with authors, leave reviews, or publish your own stories to an active community.</p>
            </div>
        </div>
    </div>
</div>

<!-- Trending Books Section (Sleek Horizontal Layout) -->
<div class="py-32 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 animate-slide-up-fade">
            <div>
                <h2 class="text-3xl md:text-5xl font-black tracking-tight text-zinc-900 mb-4">Trending Now</h2>
                <p class="text-lg text-zinc-500 font-medium">Discover what the community is reading today.</p>
            </div>
            <a href="{{ route('books.index') }}" class="inline-flex items-center text-zinc-900 font-semibold hover:text-accent-600 transition-colors group">
                View catalog
                <span class="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center ml-3 group-hover:bg-accent-50 transition-colors">
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
            </a>
        </div>

        @if($featuredBooks->isEmpty())
            <div class="py-20 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-zinc-900">No books yet</p>
                <p class="text-sm text-zinc-400 mt-1">Check back later or publish the first story.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-5 gap-y-8">
                @foreach($featuredBooks->take(4) as $book)
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
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-1.5">{{ $book->author->name ?? '' }}</p>
                                    <h3 class="font-serif text-sm font-semibold text-white leading-snug line-clamp-3">{{ $book->title }}</h3>
                                </div>
                            </div>
                        @endif
                        <div class="absolute inset-0 rounded-lg ring-1 ring-inset ring-black/10 pointer-events-none"></div>
                    </div>

                    <!-- Meta -->
                    <h2 class="text-sm font-semibold text-zinc-900 group-hover:text-zinc-500 transition-colors line-clamp-1 leading-snug">{{ $book->title }}</h2>
                    <p class="text-xs text-zinc-400 mt-0.5 line-clamp-1">{{ $book->author->name ?? 'Unknown Author' }}</p>

                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Popular Authors Section -->
<div class="py-24 bg-zinc-50 border-t border-zinc-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-black tracking-tight text-zinc-900 mb-2">Popular Authors</h2>
                <p class="text-base text-zinc-500">Writers building the library one story at a time.</p>
            </div>
        </div>

        @if($popularAuthors->isEmpty())
            <div class="py-16 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-zinc-900">No authors yet</p>
                <p class="text-sm text-zinc-400 mt-1">Be the first to publish a story.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($popularAuthors as $author)
                <a href="{{ route('author.profile', $author->id) }}"
                   class="group flex items-center gap-4 p-5 bg-white border border-zinc-200 rounded-xl hover:border-zinc-300 hover:bg-zinc-50 transition-colors">

                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-white font-semibold text-lg font-serif shrink-0 overflow-hidden group-hover:bg-zinc-700 transition-colors">
                        @if($author->profile_picture)
                            <img src="{{ Storage::url($author->profile_picture) }}" alt="{{ $author->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($author->name, 0, 1) }}
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-zinc-900 truncate group-hover:text-zinc-600 transition-colors">{{ $author->name }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">
                            {{ $author->books_count }} {{ $author->books_count === 1 ? 'book' : 'books' }}
                        </p>
                        @if($author->bio)
                            <p class="text-xs text-zinc-400 mt-1 line-clamp-1">{{ $author->bio }}</p>
                        @endif
                    </div>

                    <!-- Arrow -->
                    <svg class="w-4 h-4 text-zinc-300 group-hover:text-zinc-500 transition-colors shrink-0 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
        @endif

    </div>
</div>

<!-- CTA Section (Dark Mode) -->
<div class="py-24 bg-zinc-900 text-white relative overflow-hidden">
    <!-- Decorative Circle -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-zinc-600/20 rounded-xl blur-[100px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-4xl md:text-6xl font-black tracking-tight mb-8">Ready to write <br/>your next chapter?</h2>
        <p class="text-xl text-zinc-400 mb-12 max-w-2xl mx-auto font-medium">Join thousands of readers and writers who are shaping the future of digital storytelling.</p>

        @guest
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}" class="h-14 px-8 rounded-xl bg-white text-zinc-900 font-bold flex items-center justify-center hover:bg-zinc-100 hover:scale-105 transition-all duration-200 shadow-xl shadow-xl/10">
                Create Free Account
            </a>
            <a href="{{ route('login') }}" class="h-14 px-8 rounded-xl glass-dark font-medium flex items-center justify-center hover:bg-white/10 transition-all duration-200">
                Sign In
            </a>
        </div>
        @else
        <div class="flex justify-center">
            <a href="{{ route('books.index') }}" class="h-14 px-8 rounded-xl bg-white text-zinc-900 font-bold flex items-center justify-center hover:bg-zinc-100 hover:scale-105 transition-all duration-200 shadow-xl shadow-xl/10">
                Go to Library
            </a>
        </div>
        @endguest
    </div>
</div>
@endsection
