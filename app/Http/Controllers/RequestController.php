<?php

namespace App\Http\Controllers;

use App\Models\EventRequest;
use App\Models\BookReservation;
use App\Models\BookLending;
use App\Models\ReturnRequest;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $eventRequests = EventRequest::with(['user', 'event'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
        
        $bookReservations = BookReservation::with(['member', 'bookItem.book'])
            ->where('member_id', $userId)
            ->latest()
            ->get();
        
        $bookLendings = BookLending::with(['member', 'bookItem.book'])
            ->where('member_id', $userId)
            ->whereNull('return_date')
            ->latest()
            ->get();
        
        $returnRequests = ReturnRequest::with(['lending.bookItem.book', 'processedBy'])
            ->whereHas('lending', function($query) use ($userId) {
                $query->where('member_id', $userId);
            })
            ->latest()
            ->get();
        
        $borrowRequests = BorrowRequest::with(['member', 'bookItem.book', 'processedBy'])
            ->where('member_id', $userId)
            ->latest()
            ->get();
        
        return view('requests.index', compact('eventRequests', 'bookReservations', 'bookLendings', 'returnRequests', 'borrowRequests'));
    }

    public function storeReturnRequest(Request $request)
    {
        $request->validate([
            'lending_id' => 'required|exists:book_lendings,id',
            'member_notes' => 'nullable|string|max:500',
        ]);

        $lending = BookLending::findOrFail($request->lending_id);

        if ($lending->member_id !== Auth::id()) {
            abort(403);
        }

        if ($lending->return_date) {
            return redirect()->back()->with('error', 'Sách này đã được trả.');
        }

        $existingRequest = ReturnRequest::where('lending_id', $lending->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Bạn đã gửi yêu cầu trả sách này rồi.');
        }

        ReturnRequest::create([
            'lending_id' => $lending->id,
            'requested_date' => now(),
            'status' => 'pending',
            'member_notes' => $request->member_notes,
        ]);

        return redirect()->route('requests.index')->with('success', 'Đã gửi yêu cầu trả sách thành công!');
    }

    public function storeBorrowRequest(Request $request)
    {
        $request->validate([
            'book_item_id' => 'required|exists:book_items,id',
            'expected_borrow_date' => 'required|date',
            'member_notes' => 'nullable|string|max:500',
        ]);

        $existingRequest = BorrowRequest::where('member_id', Auth::id())
            ->where('book_item_id', $request->book_item_id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Bạn đã gửi yêu cầu mượn sách này rồi.');
        }

        BorrowRequest::create([
            'member_id' => Auth::id(),
            'book_item_id' => $request->book_item_id,
            'requested_date' => now(),
            'expected_borrow_date' => $request->expected_borrow_date,
            'status' => 'pending',
            'member_notes' => $request->member_notes,
        ]);

        return redirect()->route('requests.index')->with('success', 'Đã gửi yêu cầu mượn sách thành công!');
    }
}
