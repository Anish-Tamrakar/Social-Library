@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8 pb-6 border-b border-zinc-200">
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Settings</h1>
        <p class="text-sm text-zinc-500 mt-0.5">Manage your profile and account preferences.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-lg text-sm mb-6 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <ul class="space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Profile Picture -->
        <div>
            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-4">Profile Picture</p>
            <div class="flex items-center gap-5">

                <!-- Avatar circle -->
                <div class="relative group cursor-pointer shrink-0" onclick="document.getElementById('avatar-input').click()">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-zinc-200 flex items-center justify-center text-zinc-700 text-2xl font-serif font-semibold border-2 border-transparent group-hover:border-zinc-400 transition-colors">
                        @if($user->profile_picture)
                            <img id="avatar-preview"
                                 src="{{ Storage::url($user->profile_picture) }}"
                                 alt="{{ $user->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span id="avatar-initial">{{ substr($user->name, 0, 1) }}</span>
                            <img id="avatar-preview" src="" alt="" class="hidden w-full h-full object-cover">
                        @endif
                    </div>
                    <!-- Camera overlay -->
                    <div class="absolute inset-0 rounded-full bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>

                <input type="file" id="avatar-input" name="profile_picture" accept="image/*" class="hidden">

                <div>
                    <button type="button" onclick="document.getElementById('avatar-input').click()"
                            class="text-sm font-medium text-zinc-700 border border-zinc-200 px-3 py-1.5 rounded-lg hover:bg-zinc-50 hover:border-zinc-300 transition-colors">
                        Change photo
                    </button>
                    <p id="avatar-hint" class="text-xs text-zinc-400 mt-1.5">JPG, PNG or GIF · max 2MB</p>
                </div>
            </div>
        </div>

        <hr class="border-zinc-100">

        <!-- Public Profile -->
        <div>
            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-4">Public Profile</p>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-zinc-700 mb-1.5">Display Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors">
                </div>

                <div>
                    <label for="bio" class="block text-xs font-semibold text-zinc-700 mb-1.5">Biography</label>
                    <textarea name="bio" id="bio" rows="3"
                              class="w-full px-3 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 resize-none transition-colors placeholder-zinc-400"
                              placeholder="Tell readers a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </div>
        </div>

        <hr class="border-zinc-100">

        <!-- Password -->
        <div>
            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Change Password</p>
            <p class="text-xs text-zinc-400 mb-4">Leave blank to keep your current password.</p>
            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-700 mb-1.5">New Password</label>
                    <input type="password" name="password" id="password"
                           class="w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full h-10 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="pt-2 flex items-center justify-end gap-3 border-t border-zinc-100">
            <a href="{{ route('profile') }}"
               class="px-4 h-10 flex items-center text-sm font-medium text-zinc-600 border border-zinc-200 rounded-lg hover:bg-zinc-50 hover:border-zinc-300 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-5 h-10 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-700 transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        document.getElementById('avatar-hint').textContent = file.name;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const initial = document.getElementById('avatar-initial');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (initial) initial.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush

@endsection
