<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->input('email')),
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            'role'             => ['required', 'in:author,reader'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'dob'              => ['nullable', 'date'],
            'profile_picture'  => ['nullable', 'image', 'max:2048'],
        ]);

        // auto-generate unique username
        $baseUsername = strtolower(explode(' ', $request->name)[0]);
        $username = $baseUsername . rand(1000, 9999);
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . rand(1000, 9999);
        }

        $picturePath = null;
        if ($request->hasFile('profile_picture')) {
            $picturePath = $request->file('profile_picture')->store('avatars', 'public');
        }

        $user = User::create([
            'name'            => $request->name,
            'username'        => $username,
            'email'           => strtolower($request->email),
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'phone'           => $request->phone,
            'dob'             => $request->dob,
            'profile_picture' => $picturePath,
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
