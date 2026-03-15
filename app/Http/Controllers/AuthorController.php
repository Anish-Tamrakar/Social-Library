<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class AuthorController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'author') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $books = Book::where('author_id', $user->id)->get();
        $publishedBooks = $books->where('status', 'published');
        $pendingBooks = $books->where('status', 'pending');
        $rejectedBooks = $books->where('status', 'rejected');

        // Mock analytics data for simplicity
        $analytics = [
            'total_reads' => 1250,
            'new_followers' => 42,
            'average_rating' => 4.6,
            'earnings' => '$145.00'
        ];

        return view('author.dashboard', compact('publishedBooks', 'pendingBooks', 'rejectedBooks', 'analytics'));
    }
}
