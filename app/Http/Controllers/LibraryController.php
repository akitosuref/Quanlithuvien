<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\BookLending;
use App\Models\BookReservation;
use App\Models\User;

class LibraryController extends Controller
{
    // Hiển thị Dashboard
    public function dashboard()
    {
        $user = Auth::user() ?? User::find(1);
        return view('dashboard', compact('user'));
    }

    // R14: Tìm kiếm sách
    public function searchCatalog(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('search_type', 'title');

        if (!$query) {
            return back()->with('error', 'Vui lòng nhập từ khóa tìm kiếm.');
        }

        $results = Book::query();

        switch ($type) {
            case 'isbn':
                $results->where('isbn', 'LIKE', "%{$query}%");
                break;
            case 'subject':
                $results->where('subject', 'LIKE', "%{$query}%");
                break;
            case 'title':
            default:
                $results->where('title', 'LIKE', "%{$query}%");
                break;
        }

        $results = $results->with([
            'bookItems' => function ($query) {
                $query->with('rack');
            }
        ])->paginate(10);

        return view('dashboard', [
            'user' => Auth::user(),
            'searchResults' => $results,
            'searchQuery' => $query,
            'searchType' => $type,
        ]);
    }

    // R13: Đặt giữ sách
    public function reserveBook(Request $request, BookItem $bookItem)
    {
        $member = Auth::user();
        if (!$member || !$member->isMember()) {
            return back()->with('error', 'Chỉ thành viên mới có thể đặt giữ sách.');
        }

        if ($bookItem->isAvailable() || \App\Models\BookReservation::isReservedByAnyone($bookItem)) {
            return back()->with('error', 'Sách không thể đặt giữ lúc này.');
        }

        try {
            \App\Models\BookReservation::create([
                'member_id' => $member->id,
                'book_item_id' => $bookItem->id,
                'reservation_date' => now(),
                'status' => 'WAITING',
            ]);
            $bookItem->update(['status' => 'RESERVED']);
            return back()->with('success', 'Bạn đã đặt giữ sách thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi đặt giữ sách.');
        }
    }

    // R11: Gia hạn sách
    public function renewBook(BookLending $lending)
    {
        $member = Auth::user();
        if (!$member || $lending->member_id !== $member->id || $lending->return_date) {
            return back()->with('error', 'Lần mượn không hợp lệ hoặc sách đã được trả.');
        }

        $newDueDate = $lending->due_date->addDays(15);
        $lending->update(['due_date' => $newDueDate]);

        return back()->with('success', 'Gia hạn sách thành công. Ngày trả mới: ' . $newDueDate->toDateString());
    }

    // Thủ thư: Cấp phát sách
    public function issueBook(Request $request)
    {
        $librarian = Auth::user();
        $member = User::find($request->member_id);
        $bookItem = BookItem::find($request->book_item_id);

        if (!$librarian || !$librarian->isLibrarian()) {
            return back()->with('error', 'Bạn không có quyền thủ thư.');
        }
        if (!$member || !$member->isMember() || $member->account_status !== 'ACTIVE') {
            return back()->with('error', 'Thành viên không hợp lệ.');
        }
        if (!$bookItem || !$bookItem->isAvailable()) {
            return back()->with('error', 'Bản sao sách không có sẵn.');
        }
        if (!$member->canCheckoutBook()) {
            return back()->with('error', 'Thành viên đã đạt hạn mức mượn tối đa (10 cuốn).');
        }

        try {
            DB::beginTransaction();

            BookLending::create([
                'member_id' => $member->id,
                'book_item_id' => $bookItem->id,
                'borrowed_date' => now(),
                'due_date' => now()->addDays(15), // R8
            ]);
            $bookItem->update(['status' => 'LOANED']);

            \App\Models\BookReservation::where('member_id', $member->id)
                ->where('book_item_id', $bookItem->id)
                ->where('status', 'WAITING')
                ->update(['status' => 'PROCESSING']);

            DB::commit();
            return back()->with('success', 'Cấp phát sách thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống: Không thể cấp phát sách.');
        }
    }

    // Thủ thư: Trả sách
    public function returnBook(Request $request)
    {
        $librarian = Auth::user();
        $lending = BookLending::find($request->lending_id);

        if (!$librarian || !$librarian->isLibrarian()) {
            return back()->with('error', 'Bạn không có quyền thủ thư.');
        }
        if (!$lending || $lending->return_date) {
            return back()->with('error', 'Lần mượn không hợp lệ hoặc sách đã được trả.');
        }

        try {
            DB::beginTransaction();

            $lending->update(['return_date' => now()]);
            $bookItem = $lending->bookItem;
            $bookItem->update(['status' => 'AVAILABLE']);

            // R12: Phạt quá hạn
            if ($lending->isOverdue()) {
                $daysOverdue = now()->diffInDays($lending->due_date);
                $fineAmount = $daysOverdue * 5000;
                $lending->member->notifications()->create(['subject' => 'Thông báo Phí phạt Quá hạn', 'content' => "Sách '{$bookItem->book->title}' đã quá hạn {$daysOverdue} ngày. Tổng phí phạt: {$fineAmount} VND.", 'type' => 'EMAIL', 'lending_id' => $lending->id,]);
                DB::commit();
                return back()->with('warning', "Sách đã được trả. Quá hạn {$daysOverdue} ngày. Yêu cầu thành viên thanh toán phí phạt: {$fineAmount} VND.");
            }

            // Reservation available notification
            $nextReservation = \App\Models\BookReservation::where('book_item_id', $bookItem->id)
                ->where('status', 'WAITING')
                ->orderBy('reservation_date')
                ->first();
            if ($nextReservation) {
                $bookItem->update(['status' => 'RESERVED']);
                $nextReservation->member->notifications()->create(['subject' => 'Thông báo Sách đã có sẵn', 'content' => "Sách '{$bookItem->book->title}' bạn đặt giữ đã có sẵn để mượn.", 'type' => 'EMAIL', 'lending_id' => null,]);
            }

            DB::commit();
            return back()->with('success', 'Trả sách thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống khi trả sách.');
        }
    }
}