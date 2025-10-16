<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PhieuMuonController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Auth::routes();

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// DEBUG ROUTE - Remove after testing
Route::get('/debug-auth', function () {
    $user = auth()->user();
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => $user,
        'is_librarian' => $user ? $user->isLibrarian() : false,
    ]);
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [LibraryController::class, 'dashboard'])->name('dashboard');

    // Chức năng Chung
    Route::get('/search', [LibraryController::class, 'searchCatalog'])->name('catalog.search');

    // Posts/Social Features
    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');

    // Chức năng Thành viên (Member)
    Route::post('/books/{bookItem}/reserve', [LibraryController::class, 'reserveBook'])->name('member.reserve');
    Route::post('/lendings/{lending}/renew', [LibraryController::class, 'renewBook'])->name('member.renew');
    Route::get('/member/profile', [MemberController::class, 'profile'])->name('member.profile');
    Route::get('/member/lending-history', [MemberController::class, 'lendingHistory'])->name('member.lending-history');

    // Chức năng Thủ thư (Librarian) - Nghiệp vụ
    Route::middleware('librarian')->group(function () {
        Route::post('/books/issue', [LibraryController::class, 'issueBook'])->name('librarian.issue');
        Route::post('/lendings/return', [LibraryController::class, 'returnBook'])->name('librarian.return');

        // Chức năng Thủ thư (Librarian) - CRUD Dữ liệu
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::post('/books', [DataController::class, 'createBook'])->name('books.create');
            Route::delete('/books/{book}', [DataController::class, 'deleteBook'])->name('books.delete');
            Route::post('/members/register', [DataController::class, 'registerMember'])->name('members.register');
            Route::post('/members/{user}/cancel', [DataController::class, 'cancelMembership'])->name('members.cancel');
        });

        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::resource('members', MemberController::class)->except(['index', 'show']);
        Route::resource('phieumuon', PhieuMuonController::class);
    });

    // Books/Members Resource Routes (view only for members) - Must be AFTER librarian routes
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
});
