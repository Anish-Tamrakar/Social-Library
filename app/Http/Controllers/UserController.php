<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('settings')->with('success', 'Profile updated successfully.');
    }
}
