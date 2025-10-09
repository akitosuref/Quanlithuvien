<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Rack;
use App\Models\LibraryCard;
use App\Models\BookLending;
use App\Models\BookReservation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Yêu cầu bạn phải tạo các Factories: Address, Author, Rack, User (với states: librarian, member)
        // và các Model tương ứng để Seeder này hoạt động.

        // 1. Tạo Authors và Racks cơ bản
        $authors = Author::factory(10)->create();
        $racks = Rack::factory(5)->create();

        // 2. Tạo Users (Librarian và Member)
        // Đảm bảo có một User Admin để đăng nhập
        $librarian = User::factory()->state(['role' => 'librarian', 'password' => \Hash::make('password')])->create(['name' => 'Admin Librarian', 'email' => 'admin@lms.com',]);
        // Tạo 10 thành viên
        $members = User::factory(10)->state(['role' => 'member', 'password' => \Hash::make('password')])->create();

        // 3. Tạo Library Cards
        $allUsers = User::all();
        $allUsers->each(function ($user) {
            LibraryCard::create([
                'user_id' => $user->id,
                'card_number' => 'LMS' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'issued_at' => now(),
                'is_active' => true,
            ]);
        });

        // 4. Tạo Books và Book Items
        $authors->each(function ($author) use ($racks, $members) {
            Book::factory(rand(1, 3))->create(['author_id' => $author->id,])
                ->each(function ($book) use ($racks, $members) {
                    for ($i = 0; $i < 3; $i++) {
                        $item = BookItem::create([
                            'book_id' => $book->id,
                            'rack_id' => $racks->random()->id,
                            'barcode' => $book->isbn . '-' . ($i + 1),
                            'status' => 'AVAILABLE',
                        ]);

                        // Giả lập một số sách đã được mượn/đặt giữ
                        if ($i === 0 && rand(1, 4) === 1) { // 25% đã mượn
                            $member = $members->random();
                            $borrowed_date = now()->subDays(rand(1, 10));
                            BookLending::create([
                                'member_id' => $member->id,
                                'book_item_id' => $item->id,
                                'borrowed_date' => $borrowed_date,
                                'due_date' => $borrowed_date->addDays(15), // R8
                                'return_date' => null,
                            ]);
                            $item->update(['status' => 'LOANED']);
                        } elseif ($i === 1 && rand(1, 4) === 1) { // 25% đã đặt giữ
                            BookReservation::create([
                                'member_id' => $members->random()->id,
                                'book_item_id' => $item->id,
                                'reservation_date' => now()->subDays(rand(1, 5)),
                                'status' => 'WAITING',
                            ]);
                            $item->update(['status' => 'RESERVED']);
                        }
                    }
                });
        });

        // Tạo Lending/Reservation mock cho các ví dụ trong Controller/View (ID 1)
        $memberMock = User::where('role', 'member')->first();
        $itemMock = BookItem::where('status', 'LOANED')->first();

        if (!$itemMock) {
            // Nếu không có sách nào LOANED, tạo một bản sao để mượn mock
            $itemMock = BookItem::where('status', 'AVAILABLE')->first();
            if ($itemMock) {
                $itemMock->update(['status' => 'LOANED']);
            } else {
                // Tạo một bản sao mới nếu cần thiết
                $book = Book::first() ?? Book::factory()->create(['author_id' => $authors->first()->id]);
                $rack = $racks->first();
                $itemMock = BookItem::create([
                    'book_id' => $book->id,
                    'rack_id' => $rack->id,
                    'barcode' => 'MOCK-001',
                    'status' => 'LOANED',
                ]);
            }
        }

        if ($memberMock && $itemMock) {
            BookLending::create([
                'member_id' => $memberMock->id,
                'book_item_id' => $itemMock->id,
                'borrowed_date' => now()->subDays(5),
                'due_date' => now()->addDays(10), // Chưa quá hạn
                'return_date' => null,
            ]);
        }
    }
}