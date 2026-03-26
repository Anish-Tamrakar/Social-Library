<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $books = \App\Models\Book::where('author_id', $user->id)->where('status', 'published')->get();
        return view('user.profile', compact('user', 'books'));
    }

    public function publicProfile($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $books = \App\Models\Book::where('author_id', $user->id)->where('status', 'published')->get();
        return view('user.public-profile', compact('user', 'books'));
    }

    public function settings(Request $request)
    {
        $user = $request->user();
        return view('user.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio'             => ['nullable', 'string'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'password'        => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name  = $request->name;
        $user->email = strtolower($request->email);
        $user->bio   = $request->bio;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('settings')->with('success', 'Profile updated successfully.');
    }
}
