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
        // Here status means author's published/draft status, admin_status is the admin approval
        $publishedBooks = $books->where('status', 'published')->where('admin_status', 'approved');
        $pendingBooks = $books->where('admin_status', 'pending');
        $rejectedBooks = $books->where('admin_status', 'rejected');

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
