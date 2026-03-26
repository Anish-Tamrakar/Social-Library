<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Donation;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $today = \Carbon\Carbon::today();

        $stats = [
            'total_users' => User::count(),
            'daily_users' => User::whereDate('created_at', $today)->count(),

            'total_books' => Book::count(),
            'daily_books' => Book::whereDate('created_at', $today)->count(),

            'total_donations' => Donation::sum('amount'), // assuming `amount` column exists, else adjust
            'daily_donations' => Donation::whereDate('created_at', $today)->sum('amount'),

            'pending_books' => Book::where('admin_status', 'pending')->count(),
            'flagged_reviews' => Rating::where('is_flagged', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * List all pending books awaiting approval.
     */
    public function pendingBooks()
    {
        $books = Book::where('admin_status', 'pending')->with('author')->paginate(10);
        return view('admin.pending_books', compact('books'));
    }

    /**
     * Approve a pending book.
     */
    public function approveBook(Book $book)
    {
        $book->update(['admin_status' => 'approved']);
        return redirect()->back()->with('success', 'Book approved successfully.');
    }

    /**
     * Reject a pending book.
     */
    public function rejectBook(Book $book)
    {
        $book->update(['admin_status' => 'rejected']);
        return redirect()->back()->with('success', 'Book rejected.');
    }

    /**
     * Toggle the featured status of a book.
     */
    public function toggleFeatured(Book $book)
    {
        $book->update(['is_featured' => !$book->is_featured]);
        $status = $book->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()->with('success', "Book is now {$status}.");
    }

    /**
     * Books management list
     */
    public function manageBooks()
    {
        $books = Book::with('author')->paginate(20);
        return view('admin.manage_books', compact('books'));
    }

    /**
     * List flagged ratings/reviews.
     */
    public function flaggedRatings()
    {
        $ratings = Rating::where('is_flagged', true)->with(['user', 'book'])->paginate(10);
        return view('admin.flagged_ratings', compact('ratings'));
    }

    /**
     * Delete a flagged rating.
     */
    public function deleteRating(Rating $rating)
    {
        $rating->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }

    /**
     * Unflag a rating (mark as safe).
     */
    public function unflagRating(Rating $rating)
    {
        $rating->update(['is_flagged' => false]);
        return redirect()->back()->with('success', 'Review unflagged successfully.');
    }
}
