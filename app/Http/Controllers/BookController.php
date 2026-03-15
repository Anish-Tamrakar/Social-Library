<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Favorite;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::where('status', 'published');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('author', function($qAuthor) use ($search) {
                      $qAuthor->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('genre') && $request->genre != '') {
            $query->where('genre', $request->genre);
        }

        // Add additional sorting logic if needed
        $sort = $request->sort ?? 'newest';
        if ($sort == 'newest') {
            $query->latest();
        } else {
            // Default latest
            $query->latest();
        }

        $books = $query->with('author')->paginate(12)->withQueryString();

        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        if ($book->status !== 'published') {
            abort(404);
        }

        return view('books.show', compact('book'));
    }

    public function read(Book $book)
    {
        if ($book->status !== 'published') {
            abort(404);
        }

        if (Auth::check()) {
            ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'book_id' => $book->id],
                ['opened_at' => now()]
            );
        }

        return view('books.read', compact('book'));
    }

    public function favorites()
    {
        $favorites = Auth::user()->favorites()->with('book', 'author')->get();
        return view('books.favorites', compact('favorites'));
    }

    public function toggleFavorite(Request $request, Book $book)
    {
        $favorite = Favorite::where('user_id', Auth::id())
                            ->where('book_id', $book->id)
                            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id
            ]);
        }

        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'genre' => 'required',
            'description' => 'required',
            'pdf_file' => 'required|mimes:pdf|max:50000', // 50MB Max
        ]);

        $pdfPath = $request->file('pdf_file')->store('books/pdfs', 'public');

        Book::create([
            'title' => $request->title,
            'genre' => $request->genre,
            'summary' => $request->description,
            'author_id' => Auth::id(),
            'status' => 'published',
            'pdf_path' => $pdfPath,
        ]);

        return back()->with('success', 'Book created successfully.');
    }

    public function destroy(Book $book)
    {
        if ($book->author_id !== Auth::id()) {
            abort(403);
        }
        $book->delete();
        return back()->with('success', 'Book deleted successfully.');
    }
}
