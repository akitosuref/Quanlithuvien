<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PhieuMuonController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LibraryEventController;
use App\Http\Controllers\EventRequestController;
use App\Http\Controllers\EventResponseController;
use App\Http\Controllers\MemberEventController;
use App\Http\Controllers\RequestController;
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

    // Member Event Features
    Route::get('/library-events', [MemberEventController::class, 'index'])->name('member-events.index');
    Route::get('/library-events/{event}', [MemberEventController::class, 'show'])->name('member-events.show');
    Route::resource('event-requests', EventRequestController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::post('/event-responses', [EventResponseController::class, 'store'])->name('event-responses.store');
    Route::patch('/event-responses/{eventResponse}', [EventResponseController::class, 'update'])->name('event-responses.update');
    Route::delete('/event-responses/{eventResponse}', [EventResponseController::class, 'destroy'])->name('event-responses.destroy');

    // Member Requests
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::post('/requests/return', [RequestController::class, 'storeReturnRequest'])->name('requests.return.store');
    Route::post('/requests/borrow', [RequestController::class, 'storeBorrowRequest'])->name('requests.borrow.store');

    // Chức năng Thành viên (Member)
    Route::post('/books/{bookItem}/reserve', [LibraryController::class, 'reserveBook'])->name('member.reserve');
    Route::post('/lendings/{lending}/renew', [LibraryController::class, 'renewBook'])->name('member.renew');
    Route::get('/member/profile', [MemberController::class, 'profile'])->name('member.profile');
    Route::get('/member/lending-history', [MemberController::class, 'lendingHistory'])->name('member.lending-history');

    // Chức năng Thủ thư (Librarian) - Nghiệp vụ
    Route::middleware('librarian')->group(function () {
        Route::post('/books/issue', [LibraryController::class, 'issueBook'])->name('librarian.issue');
        Route::post('/lendings/return', [LibraryController::class, 'returnBook'])->name('librarian.return');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activity}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

        Route::resource('events', LibraryEventController::class);
        Route::get('/event-requests/{eventRequest}/review', [EventRequestController::class, 'review'])->name('event-requests.review');
        Route::patch('/event-requests/{eventRequest}/review', [EventRequestController::class, 'updateReview'])->name('event-requests.update-review');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::post('/books', [DataController::class, 'createBook'])->name('books.create');
            Route::delete('/books/{book}', [DataController::class, 'deleteBook'])->name('books.delete');
            Route::post('/members/register', [DataController::class, 'registerMember'])->name('members.register');
            Route::post('/members/{user}/cancel', [DataController::class, 'cancelMembership'])->name('members.cancel');
        });

        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::resource('members', MemberController::class)->except(['index', 'show']);
        Route::resource('phieumuon', PhieuMuonController::class);
        
        Route::patch('/phieumuon/{returnRequest}/approve-return', [PhieuMuonController::class, 'approveReturn'])->name('phieumuon.return.approve');
        Route::patch('/phieumuon/{returnRequest}/reject-return', [PhieuMuonController::class, 'rejectReturn'])->name('phieumuon.return.reject');
        Route::patch('/phieumuon/{returnRequest}/complete-return', [PhieuMuonController::class, 'completeReturn'])->name('phieumuon.return.complete');
    });

    // Books/Members Resource Routes (view only for members) - Must be AFTER librarian routes
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
});
