<?php

namespace App\Http\Controllers;

use App\Models\PhieuMuon;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\User;
use Illuminate\Http\Request;

class PhieuMuonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phieumuons = PhieuMuon::with(['bookItem.book', 'member'])->get();
        return view('phieumuon.index', compact('phieumuons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookItems = BookItem::with('book')->where('status', 'AVAILABLE')->get();
        $members = User::all();
        return view('phieumuon.create', compact('bookItems', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_item_id' => 'required|exists:book_items,id',
            'member_id' => 'required|exists:users,id',
            'borrowed_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrowed_date',
        ]);

        $bookItem = BookItem::findOrFail($request->book_item_id);
        if ($bookItem->status !== 'AVAILABLE') {
            return redirect()->back()->with('error', 'Sách này không có sẵn để mượn.');
        }

        PhieuMuon::create($request->all());

        $bookItem->update(['status' => 'LOANED']);

        return redirect()->route('phieumuon.index')
            ->with('success', 'Phiếu mượn đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PhieuMuon $phieumuon)
    {
        return view('phieumuon.show', compact('phieumuon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhieuMuon $phieumuon)
    {
        $bookItems = BookItem::with('book')->get();
        $members = User::all();
        return view('phieumuon.edit', compact('phieumuon', 'bookItems', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PhieuMuon $phieumuon)
    {
        $request->validate([
            'book_item_id' => 'required|exists:book_items,id',
            'member_id' => 'required|exists:users,id',
            'borrowed_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrowed_date',
            'return_date' => 'nullable|date|after_or_equal:borrowed_date',
        ]);

        $was_returned = $phieumuon->return_date;

        $phieumuon->update($request->all());

        if (!$was_returned && $request->return_date) {
            $phieumuon->bookItem->update(['status' => 'AVAILABLE']);
        } elseif ($was_returned && !$request->return_date) {
            $phieumuon->bookItem->update(['status' => 'LOANED']);
        }

        return redirect()->route('phieumuon.index')
            ->with('success', 'Phiếu mượn đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhieuMuon $phieumuon)
    {
        if (!$phieumuon->return_date) {
            $phieumuon->bookItem->update(['status' => 'AVAILABLE']);
        }

        $phieumuon->delete();

        return redirect()->route('phieumuon.index')
            ->with('success', 'Phiếu mượn đã được xóa thành công.');
    }
}