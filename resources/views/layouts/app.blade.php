<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Social Library') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|newsreader:400,400i,700,700i" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Newsreader', 'serif'],
                    },
                    colors: {
                        accent: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                        surface: '#ffffff',
                        background: '#fafafa',
                    },
                    boxShadow: {
                        'subtle': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'floating': '0 10px 40px -10px rgba(0,0,0,0.08)',
                        'glow': '0 0 20px rgba(124, 58, 237, 0.15)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::selection { background: #e0e7ff; color: #4338ca; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }
        .text-balance { text-wrap: balance; }
        .bento-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -2px rgba(0,0,0,0.02);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .bento-card:hover {
            transform: translateY(-4px) scale(1.005);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
            border-color: #e2e8f0;
        }
        /* Hide scrollbar for clean horizontal scrolls */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-background text-zinc-900 font-sans antialiased min-h-screen flex flex-col selection:bg-accent-200 selection:text-accent-900">

    <!-- Clean, Glass Navigation -->
    <header class="glass-nav fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white font-serif font-bold text-lg group-hover:bg-accent-600 transition-colors duration-300">
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
            <div class="flex items-center gap-4">
                @auth
                    <!-- Authenticated Links -->
                    <div class="hidden md:flex items-center gap-4 border-r border-zinc-200 pr-4 mr-1">
                        @if(auth()->user()->role === 'author')
                            <a href="{{ route('author.dashboard') }}" class="text-sm font-medium text-zinc-500 hover:text-accent-600 transition-colors">Dashboard</a>
                        @endif
                        <a href="{{ route('favorites') }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Saved</a>
                    </div>

                    <!-- User Dropdown (Simulated via hover for simplicity) -->
                    <div class="relative group cursor-pointer">
                        <div class="flex items-center gap-2 pb-1">
                            <div class="w-8 h-8 rounded-full bg-accent-100 flex items-center justify-center text-accent-700 font-medium text-sm border border-accent-200">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-floating border border-zinc-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right scale-95 group-hover:scale-100">
                            <div class="px-4 py-2 border-b border-zinc-50 mb-1">
                                <p class="text-sm font-medium text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900">Profile</a>
                            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900">Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="block w-full text-left mt-1 border-t border-zinc-50">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Sign in</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium bg-zinc-900 text-white px-4 py-2 rounded-full hover:bg-accent-600 transition-colors shadow-subtle hover:shadow-glow">Get started</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main View Outlet -->
    <main class="flex-grow pt-24 pb-12 w-full animate-fade-in">
        @yield('content')
    </main>

    <!-- Proper Multi-Column Footer -->
    <footer class="border-t border-zinc-200 bg-white pt-16 pb-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">
                <!-- Branding & Bio -->
                <div class="col-span-2 lg:col-span-2 pr-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white font-serif font-bold text-lg shadow-sm">L</div>
                        <span class="text-2xl font-bold font-serif text-zinc-900 tracking-tight">Social Library</span>
                    </div>
                    <p class="text-sm text-zinc-500 font-medium mb-6 max-w-sm leading-relaxed">Discover, collect, and share extraordinary stories in a space designed purely for the love of reading. A better way to experience literature.</p>

                    <!-- Social Links -->
                    <div class="flex gap-4">
                        <a href="#" class="text-zinc-400 hover:text-zinc-900 transition-colors" title="Twitter">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="text-zinc-400 hover:text-zinc-900 transition-colors" title="GitHub">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="#" class="text-zinc-400 hover:text-zinc-900 transition-colors" title="Instagram">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Platform Links -->
                <div>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-wider uppercase mb-4">Platform</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Catalog</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Authors</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Trending</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Genres</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-wider uppercase mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Careers</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Press</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Legal Links -->
                <div>
                    <h3 class="text-xs font-bold text-zinc-900 tracking-wider uppercase mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">Copyright Info</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright & Credit -->
            <div class="pt-8 border-t border-zinc-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm font-medium text-zinc-500">&copy; {{ date('Y') }} Social Library Inc. All rights reserved.</p>
                <div class="flex items-center gap-2 text-sm font-medium text-zinc-500">
                    <span>Designed for readers.</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
