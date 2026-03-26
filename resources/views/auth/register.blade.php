@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 mb-24">

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">Create an account</h2>
        <p class="text-sm text-zinc-500">Join the community of readers and writers.</p>
    </div>

    <!-- Step indicator -->
    <div class="flex items-center justify-center gap-2 mb-8">
        <div id="dot1" class="w-2 h-2 rounded-full bg-zinc-900 transition-colors"></div>
        <div id="dot2" class="w-2 h-2 rounded-full bg-zinc-300 transition-colors"></div>
    </div>

    <div class="bg-white p-8 sm:p-10 rounded-xl border border-zinc-200">

        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-lg text-sm mb-6 flex items-start gap-3">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <ul class="space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="register-form">
            @csrf

            <!-- ── Step 1 ──────────────────────────────────────────── -->
            <div id="step1" class="space-y-5">

                <div>
                    <label for="name" class="block text-xs font-semibold text-zinc-700 mb-1.5">Full name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full h-11 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-700 mb-1.5">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full h-11 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors"
                        placeholder="you@example.com">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">I want to...</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="reader" class="peer sr-only" checked>
                            <div class="border border-zinc-200 rounded-lg p-3.5 text-center hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:ring-1 peer-checked:ring-zinc-900 transition-all">
                                <span class="block text-sm font-bold text-zinc-900 mb-0.5">Read</span>
                                <span class="block text-xs text-zinc-400">Discover books</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="author" class="peer sr-only">
                            <div class="border border-zinc-200 rounded-lg p-3.5 text-center hover:bg-zinc-50 peer-checked:border-zinc-900 peer-checked:ring-1 peer-checked:ring-zinc-900 transition-all">
                                <span class="block text-sm font-bold text-zinc-900 mb-0.5">Write</span>
                                <span class="block text-xs text-zinc-400">Publish works</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full h-11 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 mb-1.5">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full h-11 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 text-zinc-900 transition-colors">
                </div>

                <div id="step1-error" class="hidden text-sm text-red-500 bg-red-50 border border-red-100 px-3 py-2.5 rounded-lg"></div>

                <button type="button" onclick="goToStep2()"
                        class="w-full h-11 bg-zinc-900 text-white rounded-lg font-medium text-sm flex items-center justify-center gap-2 hover:bg-zinc-700 transition-colors mt-2">
                    Next
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- ── Step 2 ──────────────────────────────────────────── -->
            <div id="step2" class="hidden space-y-6">

                <div class="text-center">
                    <p class="text-sm font-semibold text-zinc-900 mb-1">Add a profile picture</p>
                    <p class="text-xs text-zinc-400">Optional — you can always add one later in settings.</p>
                </div>

                <!-- Avatar preview + upload trigger -->
                <div class="flex flex-col items-center gap-4">
                    <div class="relative group cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                        <!-- Circle -->
                        <div id="avatar-circle"
                             class="w-24 h-24 rounded-full bg-zinc-100 border-2 border-dashed border-zinc-300 flex items-center justify-center overflow-hidden transition-colors group-hover:border-zinc-400">
                            <!-- Placeholder letter (shown before upload) -->
                            <span id="avatar-initial" class="text-3xl font-bold font-serif text-zinc-400 select-none"></span>
                            <!-- Image preview (hidden until file chosen) -->
                            <img id="avatar-preview" src="" alt="Preview"
                                 class="hidden absolute inset-0 w-full h-full object-cover rounded-full">
                        </div>
                        <!-- Overlay on hover -->
                        <div class="absolute inset-0 rounded-full bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>

                    <input id="avatar-input" type="file" name="profile_picture" accept="image/*" class="hidden">

                    <div class="text-center">
                        <button type="button" onclick="document.getElementById('avatar-input').click()"
                                class="text-sm font-medium text-zinc-600 underline underline-offset-2 hover:text-zinc-900 transition-colors">
                            Choose photo
                        </button>
                        <p id="avatar-filename" class="text-xs text-zinc-400 mt-1">JPG, PNG or GIF · max 2MB</p>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="goToStep1()"
                            class="flex-1 h-11 border border-zinc-200 text-zinc-600 rounded-lg font-medium text-sm hover:bg-zinc-50 hover:border-zinc-300 transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back
                    </button>
                    <button type="submit"
                            class="flex-1 h-11 bg-zinc-900 text-white rounded-lg font-medium text-sm hover:bg-zinc-700 transition-colors">
                        Create account
                    </button>
                </div>
            </div>

        </form>

        <div class="mt-8 pt-6 border-t border-zinc-100 text-center">
            <p class="text-sm text-zinc-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-zinc-900 font-semibold hover:underline underline-offset-2 transition-colors">Sign in</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function goToStep2() {
        const name     = document.getElementById('name').value.trim();
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirm  = document.getElementById('password_confirmation').value;
        const errBox   = document.getElementById('step1-error');

        if (!name || !email || !password || !confirm) {
            showError('Please fill in all fields.'); return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showError('Please enter a valid email address.'); return;
        }
        if (password.length < 8) {
            showError('Password must be at least 8 characters.'); return;
        }
        if (password !== confirm) {
            showError('Passwords do not match.'); return;
        }

        errBox.classList.add('hidden');

        // Set the initial letter in the avatar circle
        document.getElementById('avatar-initial').textContent = name.charAt(0).toUpperCase();

        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
        document.getElementById('dot1').classList.replace('bg-zinc-900', 'bg-zinc-300');
        document.getElementById('dot2').classList.replace('bg-zinc-300', 'bg-zinc-900');
    }

    function goToStep1() {
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step1').classList.remove('hidden');
        document.getElementById('dot2').classList.replace('bg-zinc-900', 'bg-zinc-300');
        document.getElementById('dot1').classList.replace('bg-zinc-300', 'bg-zinc-900');
    }

    function showError(msg) {
        const errBox = document.getElementById('step1-error');
        errBox.textContent = msg;
        errBox.classList.remove('hidden');
    }

    // Avatar file preview
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        document.getElementById('avatar-filename').textContent = file.name;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const initial = document.getElementById('avatar-initial');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            initial.classList.add('hidden');
            // Solid border once image is selected
            document.getElementById('avatar-circle').classList.replace('border-dashed', 'border-solid');
        };
        reader.readAsDataURL(file);
    });

    // If the form submits back with errors, show step 1
    @if($errors->any())
        // errors exist — stay on step 1 (default)
    @endif
</script>
@endpush

@endsection
