<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Favorite;
use App\Models\Rating;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'books');

        // Books query
        $query = Book::where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('author', function($qAuthor) use ($search) {
                      $qAuthor->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        $books = $query->with('author')->latest()->paginate(12)->withQueryString();

        // Authors query
        $authorQuery = \App\Models\User::where('role', 'author');

        if ($request->filled('search')) {
            $search = $request->search;
            $authorQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $authors = $authorQuery->withCount('books')->orderByDesc('books_count')->paginate(12)->withQueryString();

        return view('books.index', compact('books', 'authors', 'tab'));
    }

    public function show(Book $book)
    {
        if ($book->status !== 'published') {
            abort(404);
        }

        $ratings    = $book->ratings()->with('user')->latest()->get();
        $userRating = Auth::check() ? $ratings->firstWhere('user_id', Auth::id()) : null;
        $avgRating  = $ratings->count() > 0 ? round($ratings->avg('rating'), 1) : null;

        return view('books.show', compact('book', 'ratings', 'userRating', 'avgRating'));
    }

    public function read(Book $book)
    {
        if ($book->status !== 'published') {
            abort(404);
        }

        $lastPage = 0;

        if (Auth::check()) {
            $history = ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'book_id' => $book->id],
                ['opened_at' => now()]
            );
            $lastPage = $history->current_page ?? 0;
        }

        return view('books.read', compact('book', 'lastPage'));
    }

    public function favorites()
    {
        $favorites = Auth::user()->favorites()->with('book', 'author')->get();
        return view('books.favorites', compact('favorites'));
    }

    public function history()
    {
        $history = ReadingHistory::where('user_id', Auth::id())
            ->with(['book', 'book.author'])
            ->orderBy('opened_at', 'desc')
            ->get();
        return view('books.history', compact('history'));
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

    public function rate(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            ['rating'  => $request->rating, 'review' => $request->review]
        );

        return back()->with('review_success', true);
    }

    public function deleteReview(Book $book)
    {
        Rating::where('user_id', Auth::id())->where('book_id', $book->id)->delete();
        return back();
    }

    public function suggestions(Request $request)
    {
        $q   = trim($request->get('q', ''));
        $tab = $request->get('tab', 'books');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        if ($tab === 'authors') {
            $results = \App\Models\User::where('role', 'author')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('bio', 'like', "%{$q}%");
                })
                ->withCount('books')
                ->limit(6)
                ->get()
                ->map(fn($a) => [
                    'type'            => 'author',
                    'url'             => route('author.profile', $a->id),
                    'name'            => $a->name,
                    'books_count'     => $a->books_count,
                    'profile_picture' => $a->profile_picture ? Storage::url($a->profile_picture) : null,
                ]);
        } else {
            $results = Book::where('status', 'published')
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhereHas('author', fn($qa) => $qa->where('name', 'like', "%{$q}%"));
                })
                ->with('author')
                ->limit(6)
                ->get()
                ->map(fn($b) => [
                    'type'        => 'book',
                    'url'         => route('books.show', $b->id),
                    'title'       => $b->title,
                    'author'      => $b->author->name ?? 'Unknown',
                    'genre'       => $b->genre,
                    'cover_image' => $b->cover_image ? Storage::url($b->cover_image) : null,
                ]);
        }

        return response()->json($results);
    }

    public function updateProgress(Request $request, Book $book)
    {
        if (Auth::check()) {
            $page = $request->input('page');
            ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'book_id' => $book->id],
                ['current_page' => $page, 'opened_at' => now()]
            );
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 401);
    }
}
