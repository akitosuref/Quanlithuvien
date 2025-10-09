<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PhieuMuonController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Auth::routes();

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/', [LibraryController::class, 'dashboard'])->name('dashboard');

    // Chức năng Chung
    Route::get('/search', [LibraryController::class, 'searchCatalog'])->name('catalog.search');

    // Books Resource Routes
    Route::resource('books', BookController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('members', MemberController::class);
    Route::resource('phieumuon', PhieuMuonController::class);

    // Posts/Social Features
    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');

    // Chức năng Thành viên (Member)
    Route::post('/books/{bookItem}/reserve', [LibraryController::class, 'reserveBook'])->name('member.reserve');
    Route::post('/lendings/{lending}/renew', [LibraryController::class, 'renewBook'])->name('member.renew');

    // Chức năng Thủ thư (Librarian) - Nghiệp vụ
    Route::post('/books/issue', [LibraryController::class, 'issueBook'])->name('librarian.issue');
    Route::post('/lendings/return', [LibraryController::class, 'returnBook'])->name('librarian.return');

    // Chức năng Thủ thư (Librarian) - CRUD Dữ liệu
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/books', [DataController::class, 'createBook'])->name('books.create');
        Route::delete('/books/{book}', [DataController::class, 'deleteBook'])->name('books.delete');
        Route::post('/members/register', [DataController::class, 'registerMember'])->name('members.register');
        Route::post('/members/{user}/cancel', [DataController::class, 'cancelMembership'])->name('members.cancel');
    });
});