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
        $racks = Rack::factory(10)->create();

        $this->call(UserSeeder::class);
        
        $members = User::factory(10)->state(['role' => 'member', 'password' => \Hash::make('password')])->create();

        $allUsers = User::all();
        $allUsers->each(function ($user) {
            if (!$user->libraryCard) {
                LibraryCard::create([
                    'user_id' => $user->id,
                    'card_number' => 'LC' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                    'issued_at' => now(),
                    'is_active' => true,
                ]);
            }
        });

        $this->call(BookSeeder::class);
        
        $books = Book::all();
        $books->each(function ($book) use ($members) {
            $bookItems = $book->bookItems;
            
            if ($bookItems->count() > 0 && rand(1, 3) === 1) {
                $item = $bookItems->random();
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
            }
        });
    }
}