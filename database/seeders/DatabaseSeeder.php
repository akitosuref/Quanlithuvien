<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Tạo Racks và Users
        $racks = Rack::factory(5)->create();

        // Tạo Users (Librarian và Member)
        $librarian = User::factory()->state(['role' => 'librarian', 'password' => \Hash::make('password')])->create(['name' => 'Admin Librarian', 'email' => 'admin@lms.com']);
        
        // Tạo user từ UserSeeder
        $this->call(UserSeeder::class);
        
        // Tạo 10 thành viên
        $members = User::factory(10)->state(['role' => 'member', 'password' => \Hash::make('password')])->create();

        // Tạo Library Cards
        $allUsers = User::all();
        $allUsers->each(function ($user) {
            LibraryCard::create([
                'user_id' => $user->id,
                'card_number' => 'LMS' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'issued_at' => now(),
                'is_active' => true,
            ]);
        });

        // Tạo Books và Book Items (không cần author)
        $books = Book::factory(30)->create();
        
        $books->each(function ($book) use ($racks, $members) {
            for ($i = 0; $i < 3; $i++) {
                $item = BookItem::create([
                    'book_id' => $book->id,
                    'rack_id' => $racks->random()->id,
                    'barcode' => $book->isbn . '-' . ($i + 1),
                    'status' => 'AVAILABLE',
                ]);

                // Giả lập một số sách đã được mượn/đặt giữ
                if ($i === 0 && rand(1, 4) === 1) {
                    $member = $members->random();
                    $borrowed_date = now()->subDays(rand(1, 10));
                    BookLending::create([
                        'member_id' => $member->id,
                        'book_item_id' => $item->id,
                        'borrowed_date' => $borrowed_date,
                        'due_date' => $borrowed_date->addDays(15),
                        'return_date' => null,
                    ]);
                    $item->update(['status' => 'LOANED']);
                } elseif ($i === 1 && rand(1, 4) === 1) {
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
    }
}