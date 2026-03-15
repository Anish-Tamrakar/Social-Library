@extends('layouts.app')

@push('styles')
<style>
    /* Keyframes for Animations */
    @keyframes slide-up-fade {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Custom Animation Classes */
    .animate-slide-up-fade {
        animation: slide-up-fade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0; /* Starts hidden before animation */
    }

    /* Staggered animation delays */
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }

    /* Glassmorphism Panel */
    .glass-panel {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
    }

    /* Water Fill Text Hover Effect */
    .hero-text-water {
        color: #18181b; /* zinc-900 */
        transition: color 0.3s ease-out, -webkit-text-stroke 0.3s ease-out;
        cursor: default;
    }
    .hero-text-water:hover {
        color: transparent;
        -webkit-text-stroke: 2px #18181b;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1000 200'%3E%3Cpath fill='%230284c7' opacity='0.9' d='M0,100 C250,160 250,40 500,100 C750,160 750,40 1000,100 L1000,250 L0,250 Z'/%3E%3Cpath fill='%2338bdf8' opacity='0.6' d='M0,100 C250,40 250,160 500,100 C750,40 750,160 1000,100 L1000,250 L0,250 Z'/%3E%3C/svg%3E") repeat-x;
        background-size: 200% 120%;
        -webkit-background-clip: text;
        background-clip: text;
        animation:
            fill-y 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards,
            wave-x 2s linear infinite;
    }

    @keyframes fill-y {
        0% { background-position-y: 150%; }
        100% { background-position-y: 40%; }
    }
    @keyframes wave-x {
        0% { background-position-x: 0%; }
        100% { background-position-x: -200%; }
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

    <!-- Hero Section: Modern, Typography-First with Animations -->
    <div class="relative pt-24 pb-32 flex flex-col items-center justify-center min-h-[75vh] text-center">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/80 backdrop-blur-sm border border-zinc-200/60 text-xs font-medium text-zinc-600 mb-8 shadow-sm animate-slide-up-fade">
            <span class="px-2 py-0.5 rounded-full bg-zinc-900 text-white font-bold text-[10px] uppercase tracking-wider">New</span>
            A new chapter in digital reading
        </div>

        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter mb-6 text-balance max-w-4xl mx-auto animate-slide-up-fade delay-100 hero-text-water">
            Your library, <br>
            <span>reimagined.</span>
        </h1>

        <p class="text-lg md:text-xl text-zinc-600 max-w-2xl mx-auto mb-10 font-medium animate-slide-up-fade delay-200 leading-relaxed">
            Discover, collect, and share extraordinary stories in a space designed purely for the love of reading and community.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-4 animate-slide-up-fade delay-300 w-full sm:w-auto px-4 sm:px-0">
            <a href="{{ route('books.index') }}" class="w-full sm:w-auto h-14 px-8 rounded-full bg-zinc-900 text-white font-medium flex items-center justify-center hover:bg-zinc-800 hover:-translate-y-1 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl group">
                Explore the catalog
                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
            @guest
            <a href="{{ route('register') }}" class="w-full sm:w-auto h-14 px-8 rounded-full bg-white/80 backdrop-blur-sm text-zinc-900 font-medium flex items-center justify-center border border-zinc-200 shadow-sm hover:border-zinc-300 hover:bg-zinc-50 hover:-translate-y-1 transition-all duration-300">
                Become a member
            </a>
            @endguest
        </div>
    </div>

    <!-- Bento Grid Section for Features -->
    <div class="py-24 relative z-10">
        <div class="flex items-center justify-between mb-12 animate-slide-up-fade delay-200">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-zinc-900">Why Social Library?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="glass-panel rounded-3xl p-8 md:col-span-2 flex flex-col justify-between h-80 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 animate-slide-up-fade delay-100">
                <div class="absolute right-0 top-0 w-64 h-64 bg-zinc-50 rounded-full opacity-50 -mr-20 -mt-20 transition-transform duration-700 group-hover:scale-125 group-hover:bg-zinc-100"></div>
                <div class="w-12 h-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center mb-auto shadow-md transform group-hover:rotate-12 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold text-zinc-900 mb-2 group-hover:text-zinc-600 transition-colors">Immersive Reading.</h3>
                    <p class="text-zinc-600 font-medium text-lg leading-relaxed">Experience reading like never before with our distraction-free, beautifully crafted reader interface.</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="glass-panel rounded-3xl p-8 flex flex-col justify-between h-80 hover:-translate-y-1 transition-transform duration-300 animate-slide-up-fade delay-200 group">
                <div class="w-12 h-12 rounded-2xl bg-white border border-zinc-200 text-zinc-900 flex items-center justify-center mb-auto shadow-sm transform group-hover:-rotate-12 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-2 group-hover:text-zinc-600 transition-colors">Curate Collections</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Organize your favorite stories into highly personalized, shareable libraries across the social network.</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="glass-panel rounded-3xl p-8 flex flex-col justify-between h-80 hover:-translate-y-1 transition-transform duration-300 animate-slide-up-fade delay-300 group">
                <div class="w-12 h-12 rounded-2xl bg-zinc-100 text-zinc-900 flex items-center justify-center mb-auto transform group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-2 group-hover:text-zinc-600 transition-colors">Social Hub</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Publish your works and connect with an engaging community of active, passionate readers and writers.</p>
                </div>
            </div>

            <!-- Trending Books (Database Mock) -->
            <div class="glass-panel rounded-3xl p-8 md:col-span-2 relative overflow-hidden group animate-slide-up-fade delay-400">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-zinc-900">Trending Now</h3>
                    <a href="{{ route('books.index') }}" class="text-sm font-medium text-zinc-900 hover:text-zinc-600 transition-colors flex items-center">
                        View all
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4 pt-2 -mx-4 px-4 mask-fade-edges">
                    @foreach($featuredBooks->take(3) as $book)
                    <a href="{{ route('books.show', $book->id) }}" class="flex-shrink-0 w-48 group/book cursor-pointer shadow-sm rounded-xl border border-zinc-100 p-3 bg-white/50 hover:bg-white hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-[2/3] w-full bg-zinc-200 rounded-lg mb-4 overflow-hidden relative shadow-inner">
                            <!-- Faux Book Cover -->
                            <div class="absolute inset-0 bg-zinc-800 flex p-4 items-end transform group-hover/book:scale-105 transition-transform duration-500">
                                <span class="text-zinc-300 font-serif text-xs font-bold leading-tight">{{ $book->title }}</span>
                            </div>
                        </div>
                        <h4 class="font-bold text-sm text-zinc-900 truncate group-hover/book:text-zinc-600 transition-colors">{{ $book->title }}</h4>
                        <p class="text-xs text-zinc-500 truncate mt-1">{{ explode(' ', $book->author_name)[0] }}</p>
                    </a>
                    @endforeach

                    @if($featuredBooks->isEmpty())
                        <div class="w-full text-center py-12 border-2 border-dashed border-zinc-200/60 rounded-2xl text-zinc-400 text-sm font-medium">
                            No books published yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
