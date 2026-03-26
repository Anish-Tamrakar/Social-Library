<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;

use App\Models\Book;
use App\Models\User;

Route::get('/', function () {
    $featuredBooks = Book::with('author')
        ->where('status', 'published')
        ->where('admin_status', 'approved')
        ->orderByDesc('is_featured')
        ->latest()
        ->take(4)
        ->get();

    $popularAuthors = User::where('role', 'author')
        ->withCount(['books' => function ($query) {
            $query->where('status', 'published')->where('admin_status', 'approved');
        }])
        ->orderByDesc('books_count')
        ->take(4)
        ->get();

    return view('welcome', compact('featuredBooks', 'popularAuthors'));
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/browse', [BookController::class, 'index'])->name('books.index');
Route::get('/search/suggestions', [BookController::class, 'suggestions'])->name('search.suggestions');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/read', [BookController::class, 'read'])->name('books.read');
Route::get('/author/{id}', [\App\Http\Controllers\UserController::class, 'publicProfile'])->name('author.profile');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AuthorController::class, 'dashboard'])->name('author.dashboard');
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'show'])->name('profile');
    Route::get('/settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\UserController::class, 'updateSettings'])->name('settings.update');
    Route::get('/favorites', [BookController::class, 'favorites'])->name('favorites');
    Route::get('/history', [BookController::class, 'history'])->name('history');
    Route::post('/books/{book}/progress', [BookController::class, 'updateProgress'])->name('books.progress');
    Route::post('/books/{book}/favorite', [BookController::class, 'toggleFavorite'])->name('books.favorite');
    Route::post('/books/{book}/rate', [BookController::class, 'rate'])->name('books.rate');
    Route::delete('/books/{book}/review', [BookController::class, 'deleteReview'])->name('books.review.delete');
    Route::post('/ratings/{rating}/flag', [BookController::class, 'flagReview'])->name('ratings.flag');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::post('/authors/{user}/donate', [BookController::class, 'donate'])->name('authors.donate');
});


// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/books/pending', [\App\Http\Controllers\AdminController::class, 'pendingBooks'])->name('books.pending');
    Route::get('/books/manage', [\App\Http\Controllers\AdminController::class, 'manageBooks'])->name('books.manage');
    Route::post('/books/{book}/approve', [\App\Http\Controllers\AdminController::class, 'approveBook'])->name('books.approve');
    Route::post('/books/{book}/reject', [\App\Http\Controllers\AdminController::class, 'rejectBook'])->name('books.reject');
    Route::post('/books/{book}/toggle-featured', [\App\Http\Controllers\AdminController::class, 'toggleFeatured'])->name('books.toggle-featured');

    Route::get('/ratings/flagged', [\App\Http\Controllers\AdminController::class, 'flaggedRatings'])->name('ratings.flagged');
    Route::delete('/ratings/{rating}', [\App\Http\Controllers\AdminController::class, 'deleteRating'])->name('ratings.delete');
    Route::post('/ratings/{rating}/unflag', [\App\Http\Controllers\AdminController::class, 'unflagRating'])->name('ratings.unflag');
});
