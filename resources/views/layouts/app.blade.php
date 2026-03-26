<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Social Library') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|newsreader:400,400i,700,700i" rel="stylesheet" />

    <!-- Apply dark mode before paint to prevent flash -->
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark' ||
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Newsreader', 'serif'],
                    },
                    colors: {
                        accent: {
                            50: '#fafafa', 100: '#f4f4f5', 200: '#e4e4e7',
                            300: '#d4d4d8', 400: '#a1a1aa', 500: '#71717a',
                            600: '#52525b', 700: '#3f3f46', 800: '#27272a', 900: '#18181b',
                        },
                        surface: '#ffffff',
                        background: '#fafafa',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>
    <style>
        ::selection { background: #e4e4e7; color: #18181b; }

        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid #e4e4e7;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        html.dark .glass-nav {
            background: rgba(9, 9, 11, 0.95);
            border-bottom-color: #27272a;
        }

        .text-balance { text-wrap: balance; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ── Dark mode page-content overrides ─────────────────────────── */
        html.dark body        { background-color: #09090b; color: #f4f4f5; }

        /* Surfaces */
        html.dark .bg-white   { background-color: #18181b !important; }
        html.dark .bg-zinc-50 { background-color: #09090b !important; }
        html.dark .bg-zinc-100{ background-color: #27272a !important; }

        /* Borders */
        html.dark .border-zinc-200 { border-color: #27272a !important; }
        html.dark .border-zinc-100 { border-color: #3f3f46 !important; }

        /* Text */
        html.dark .text-zinc-900 { color: #f4f4f5   !important; }
        html.dark .text-zinc-700 { color: #d4d4d8   !important; }
        html.dark .text-zinc-600 { color: #a1a1aa   !important; }
        html.dark .text-zinc-500 { color: #71717a   !important; }
        html.dark .text-zinc-400 { color: #52525b   !important; }

        /* Hover surfaces */
        html.dark .hover\:bg-zinc-50:hover  { background-color: #18181b !important; }
        html.dark .hover\:bg-zinc-100:hover { background-color: #27272a !important; }
        html.dark .divide-zinc-100 > * + *  { border-color: #27272a !important; }
        html.dark .divide-zinc-200 > * + *  { border-color: #27272a !important; }

        /* Inputs */
        html.dark input, html.dark textarea, html.dark select {
            background-color: #27272a !important;
            border-color: #3f3f46 !important;
            color: #f4f4f5 !important;
        }
        html.dark input::placeholder, html.dark textarea::placeholder { color: #52525b !important; }

        /* Dropdown */
        html.dark .group:hover .group-hover\:opacity-100,
        html.dark .group-hover\:opacity-100 {
            background-color: #18181b;
        }

        /* Keep intentionally dark elements unchanged */
        html.dark .bg-zinc-900 { background-color: #18181b !important; }
        html.dark .bg-zinc-800 { background-color: #27272a !important; }
        html.dark .text-white  { color: #ffffff !important; }

        /* Pattern dot adjust */
        html.dark .pattern-dots {
            background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-background text-zinc-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Navigation -->
    <header class="glass-nav fixed top-0 w-full z-50 transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white font-serif font-bold text-lg">
                        L
                    </div>
                    <span class="font-bold text-lg tracking-tight text-zinc-900">Social Library</span>
                </a>

                <!-- Main Nav Links -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Home</a>
                    <a href="{{ route('books.index') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Explore</a>
                </nav>
            </div>

            <!-- Right Nav -->
            <div class="flex items-center gap-3">

                <!-- Dark Mode Toggle -->
                <button id="theme-toggle" onclick="toggleTheme()"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition-colors"
                        title="Toggle dark mode">
                    <!-- Sun (shown in dark mode) -->
                    <svg id="icon-sun" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Moon (shown in light mode) -->
                    <svg id="icon-moon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @auth
                    <!-- Auth Links -->
                    <div class="hidden md:flex items-center gap-4 border-r border-zinc-200 pr-3 mr-1">
                        @if(auth()->user()->role === 'author')
                            <a href="{{ route('author.dashboard') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Dashboard</a>
                        @endif
                        <a href="{{ route('favorites') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Saved</a>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative group cursor-pointer">
                        <div class="w-8 h-8 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-700 font-medium text-sm overflow-hidden">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ Storage::url(auth()->user()->profile_picture) }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                {{ substr(auth()->user()->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-zinc-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 origin-top-right scale-95 group-hover:scale-100">
                            <div class="px-4 py-2.5 border-b border-zinc-100 mb-1">
                                <p class="text-sm font-medium text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">Profile</a>
                            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-zinc-100 mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Sign in</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium bg-zinc-900 text-white px-4 py-2 rounded-lg hover:bg-zinc-700 transition-colors">Get started</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-200 bg-white pt-16 pb-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">

                <!-- Branding -->
                <div class="col-span-2 lg:col-span-2 pr-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white font-serif font-bold text-lg">L</div>
                        <span class="text-xl font-bold font-serif text-zinc-900 tracking-tight">Social Library</span>
                    </div>
                    <p class="text-sm text-zinc-500 mb-6 max-w-sm leading-relaxed">Discover, collect, and share extraordinary stories in a space designed purely for the love of reading.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-zinc-400 hover:text-zinc-900 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="text-zinc-400 hover:text-zinc-900 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-wider uppercase mb-4">Platform</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('books.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Catalog</a></li>
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Authors</a></li>
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Trending</a></li>
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Genres</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-wider uppercase mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Careers</a></li>
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-wider uppercase mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-zinc-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-zinc-500">&copy; {{ date('Y') }} Social Library. All rights reserved.</p>
                <p class="text-sm text-zinc-400">Designed for readers.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcons(isDark);
        }

        function updateIcons(isDark) {
            document.getElementById('icon-sun').classList.toggle('hidden', !isDark);
            document.getElementById('icon-moon').classList.toggle('hidden', isDark);
        }

        // Set correct icon on load
        updateIcons(document.documentElement.classList.contains('dark'));
    </script>

    @stack('scripts')
</body>
</html>
