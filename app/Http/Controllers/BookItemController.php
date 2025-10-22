<?php

namespace App\Http\Controllers;

use App\Models\BookItem;
use App\Models\Book;
use Illuminate\Http\Request;

class BookItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1|max:50',
            'rack_id' => 'required|exists:racks,id',
            'format' => 'required|in:HARDCOVER,PAPERBACK,EBOOK,AUDIOBOOK',
        ]);

        $book = Book::findOrFail($request->book_id);
        
        $existingCount = $book->bookItems()->count();

        for ($i = 1; $i <= $request->quantity; $i++) {
            BookItem::create([
                'book_id' => $book->id,
                'rack_id' => $request->rack_id,
                'barcode' => $book->isbn . '-' . str_pad($existingCount + $i, 3, '0', STR_PAD_LEFT),
                'format' => $request->format,
                'status' => 'AVAILABLE',
            ]);
        }

        return redirect()->route('books.edit', $book->id)
            ->with('success', 'Đã thêm ' . $request->quantity . ' bản sao thành công.');
    }

    public function update(Request $request, BookItem $bookItem)
    {
        $request->validate([
            'barcode' => 'required|string|unique:book_items,barcode,' . $bookItem->id,
            'rack_id' => 'required|exists:racks,id',
            'format' => 'required|in:HARDCOVER,PAPERBACK,EBOOK,AUDIOBOOK',
        ]);

        $bookItem->update($request->only(['barcode', 'rack_id', 'format']));

        return redirect()->route('books.edit', $bookItem->book_id)
            ->with('success', 'Đã cập nhật thông tin bản sao thành công.');
    }

    public function destroy(BookItem $bookItem)
    {
        if ($bookItem->status !== 'AVAILABLE') {
            return redirect()->back()
                ->with('error', 'Không thể xóa bản sao đang được mượn hoặc đặt trước.');
        }

        $bookId = $bookItem->book_id;
        $bookItem->delete();

        return redirect()->route('books.edit', $bookId)
            ->with('success', 'Đã xóa bản sao thành công.');
    }
}
