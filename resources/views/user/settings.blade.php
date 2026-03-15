@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">Account Settings</h1>
        <p class="text-zinc-500">Manage your profile details and preferences.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-xl mb-8 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-zinc-200 rounded-3xl shadow-sm overflow-hidden">
        <form action="{{ route('settings.update') }}" method="POST" class="p-8 md:p-10">
            @csrf
            
            <div class="space-y-8">
                <!-- Profile Section -->
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Public Profile
                    </h2>
                    
                    <div class="grid gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-zinc-700 mb-2">Display Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm rounded-xl focus:ring-2 focus:ring-accent-500 focus:border-accent-500 px-4 py-3 outline-none transition-shadow" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm rounded-xl focus:ring-2 focus:ring-accent-500 focus:border-accent-500 px-4 py-3 outline-none transition-shadow" required>
                            @error('email')
                                <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-zinc-700 mb-2">Biography</label>
                            <textarea name="bio" id="bio" rows="4" class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm rounded-xl focus:ring-2 focus:ring-accent-500 focus:border-accent-500 px-4 py-3 outline-none transition-shadow placeholder-zinc-400" placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-zinc-100">

                <!-- Security Section -->
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Change Password
                    </h2>
                    <p class="text-sm text-zinc-500 mb-6">Leave blank if you don't want to change your password.</p>
                    
                    <div class="grid gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-zinc-700 mb-2">New Password</label>
                            <input type="password" name="password" id="password" class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm rounded-xl focus:ring-2 focus:ring-accent-500 focus:border-accent-500 px-4 py-3 outline-none transition-shadow">
                            @error('password')
                                <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm rounded-xl focus:ring-2 focus:ring-accent-500 focus:border-accent-500 px-4 py-3 outline-none transition-shadow">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-zinc-100 flex items-center justify-end gap-4">
                <a href="{{ route('profile') }}" class="px-6 py-2.5 bg-white text-zinc-700 font-medium text-sm rounded-xl border border-zinc-200 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-zinc-900 text-white font-medium text-sm rounded-xl hover:bg-zinc-800 transition-colors focus:ring-4 focus:ring-zinc-900/20 shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
