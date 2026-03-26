@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-20 mb-32 animate-fade-in">
    <div class="text-center mb-10">
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-zinc-900 text-white font-serif font-bold text-2xl mb-6 shadow-xl">
            L
        </a>
        <h2 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">Welcome back</h2>
        <p class="text-zinc-500 font-medium text-sm">Enter your credentials to access your account.</p>
    </div>

    <div class="bg-white p-8 sm:p-10 rounded-xl shadow-xl border border-zinc-200">
        
        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 mb-2">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                    class="w-full h-12 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900" 
                    placeholder="you@example.com">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required 
                    class="w-full h-12 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900" 
                    placeholder="••••••••">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg border-zinc-300 text-accent-600 focus:ring-accent-500 transition-colors cursor-pointer">
                    <span class="text-sm font-medium text-zinc-600 group-hover:text-zinc-900 transition-colors">Remember me</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full h-12 bg-zinc-900 text-white rounded-xl font-medium flex items-center justify-center hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-xl">
                Sign in
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm font-medium text-zinc-500">
                Don\'t have an account? 
                <a href="{{ route('register') }}" class="text-zinc-900 hover:text-accent-600 font-semibold transition-colors">Sign up</a>
            </p>
        </div>
    </div>
</div>
@endsection
