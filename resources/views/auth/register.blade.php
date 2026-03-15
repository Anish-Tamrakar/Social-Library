@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-16 mb-24 animate-fade-in">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">Create an account</h2>
        <p class="text-zinc-500 font-medium text-sm">Join the community of readers and writers.</p>
    </div>

    <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-zinc-200">
        
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-2">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                    class="w-full h-12 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 mb-2">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                    class="w-full h-12 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900" 
                    placeholder="you@example.com">
            </div>

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-2">I want to...</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer group">
                        <input type="radio" name="role" value="reader" class="peer sr-only" checked>
                        <div class="border border-zinc-200 rounded-xl p-4 text-center hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:ring-1 peer-checked:ring-zinc-900 transition-all duration-200">
                            <span class="block text-sm font-bold text-zinc-900 mb-1">Read</span>
                            <span class="block text-xs text-zinc-500">Discover books</span>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="role" value="author" class="peer sr-only">
                        <div class="border border-zinc-200 rounded-xl p-4 text-center hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:ring-1 peer-checked:ring-zinc-900 transition-all duration-200">
                            <span class="block text-sm font-bold text-zinc-900 mb-1">Write</span>
                            <span class="block text-xs text-zinc-500">Publish works</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required 
                    class="w-full h-12 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-2">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required 
                    class="w-full h-12 px-4 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-colors text-zinc-900">
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full h-12 bg-zinc-900 text-white rounded-xl mt-6 font-medium flex items-center justify-center hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-sm">
                Create account
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm font-medium text-zinc-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-zinc-900 hover:text-accent-600 font-semibold transition-colors">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
