<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;

use App\Models\Book;
use App\Models\User;

Route::get('/', function () {
    $featuredBooks = Book::with('author')->where('status', 'published')->latest()->take(4)->get();
    $popularAuthors = User::where('role', 'author')->withCount('books')->orderByDesc('books_count')->take(4)->get();

    return view('welcome', compact('featuredBooks', 'popularAuthors'));
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/browse', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/read', [BookController::class, 'read'])->name('books.read');
Route::get('/author/{id}', [\App\Http\Controllers\UserController::class, 'publicProfile'])->name('author.profile');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AuthorController::class, 'dashboard'])->name('author.dashboard');
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'show'])->name('profile');
    Route::get('/settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\UserController::class, 'updateSettings'])->name('settings.update');
    Route::get('/favorites', [BookController::class, 'favorites'])->name('favorites');
    Route::post('/books/{book}/favorite', [BookController::class, 'toggleFavorite'])->name('books.favorite');
    Route::post('/books/{book}/rate', [BookController::class, 'rate'])->name('books.rate');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::post('/authors/{user}/donate', [BookController::class, 'donate'])->name('authors.donate');
});
