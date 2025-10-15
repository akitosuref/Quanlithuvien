<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\User;
use App\Models\LibraryCard;
use Illuminate\Support\Facades\Hash;

class DataController extends Controller
{
    public function __construct()
    {
        // Yêu cầu xác thực trước
        $this->middleware('auth');
    }

    // CRUD Sách: Tạo Sách Mới (Add book INCLUDE Add book item)
    public function createBook(Request $request)
    {
        try {
            $book = Book::create($request->only('isbn', 'title', 'subject', 'publication_date'));
            for ($i = 0; $i < $request->num_copies; $i++) {
                BookItem::create([
                    'book_id' => $book->id,
                    'rack_id' => $request->rack_id,
                    'barcode' => $book->isbn . '-' . ($i + 1 + BookItem::where('book_id', $book->id)->count()),
                    'format' => $request->format ?? 'HARDCOVER',
                    'status' => 'AVAILABLE',
                ]);
            }
            return back()->with('success', 'Sách và các bản sao đã được thêm thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi thêm sách: ' . $e->getMessage());
        }
    }

    // CRUD Sách: Xóa Sách (Remove book INCLUDE Remove book item)
    public function deleteBook(Book $book)
    {
        $book->delete(); // Cascade delete sẽ xóa BookItems
        return back()->with('success', 'Sách và tất cả các bản sao đã bị xóa khỏi thư viện!');
    }

    // CRUD Thành viên: Đăng ký (Register new account INCLUDE Issue library card)
    public function registerMember(Request $request)
    {
        try {
            $address = \App\Models\Address::create($request->only('street', 'city', 'state', 'zip_code', 'country'));
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password ?? 'password'),
                'role' => 'member',
                'account_status' => 'ACTIVE',
                'address_id' => $address->id,
            ]);
            LibraryCard::create([
                'user_id' => $user->id,
                'card_number' => 'LMS' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'issued_at' => now(),
                'is_active' => true,
            ]);
            return back()->with('success', 'Đăng ký thành viên mới thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi đăng ký thành viên: ' . $e->getMessage());
        }
    }

    // CRUD Thành viên: Hủy tư cách thành viên
    public function cancelMembership(User $user)
    {
        if (!$user->isMember()) {
            return back()->with('error', 'Người dùng không phải là thành viên.');
        }
        if ($user->lendings()->whereNull('return_date')->exists()) {
            return back()->with('error', 'Thành viên vẫn còn sách chưa trả.');
        }

        $user->update(['account_status' => 'CANCELED']);
        $user->libraryCard()->update(['is_active' => false]);
        return back()->with('success', 'Tư cách thành viên đã được hủy thành công.');
    }
}