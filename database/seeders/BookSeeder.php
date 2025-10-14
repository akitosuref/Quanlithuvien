<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Charlotte và Wilbur',
                'isbn' => '9786041234567',
                'subject' => 'Văn học thiếu nhi',
                'publication_date' => '2018-01-15',
                'cover_image' => 'images/books/charlotte-va-wilbur-1.jpg',
            ],
            [
                'title' => 'Danh Tướng Việt Nam',
                'isbn' => '9786041234568',
                'subject' => 'Lịch sử',
                'publication_date' => '2019-05-20',
                'cover_image' => 'images/books/danh-tuong-viet-nam-1.jpg',
            ],
            [
                'title' => 'Mãi Mãi Cho Tôi Nếu Em Có Thể',
                'isbn' => '9786041234569',
                'subject' => 'Văn học hiện đại',
                'publication_date' => '2020-03-10',
                'cover_image' => 'images/books/mai-moi-cho-toi-neu-em-co-the-1.jpg',
            ],
            [
                'title' => 'Mẹ Lưu Manh Con Thiên Tài',
                'isbn' => '9786041234570',
                'subject' => 'Nuôi dạy con',
                'publication_date' => '2021-08-05',
                'cover_image' => 'images/books/me-luu-manh-conthien-tai-1.jpg',
            ],
            [
                'title' => 'Quỷ Bí Chỉ Chú',
                'isbn' => '9786041234571',
                'subject' => 'Truyện trinh thám',
                'publication_date' => '2019-11-12',
                'cover_image' => 'images/books/quy-bi-chi-chu-1.jpg',
            ],
            [
                'title' => 'Tiền Đẻ Ra Tiền',
                'isbn' => '9786041234572',
                'subject' => 'Tài chính - Đầu tư',
                'publication_date' => '2020-06-18',
                'cover_image' => 'images/books/tien-de-ra-tien.jpg',
            ],
            [
                'title' => 'Tinh Thần Biển',
                'isbn' => '9786041234573',
                'subject' => 'Văn học',
                'publication_date' => '2018-09-25',
                'cover_image' => 'images/books/tinh-than-bien-1.jpg',
            ],
        ];

        foreach ($books as $bookData) {
            $book = Book::create($bookData);
            
            for ($i = 1; $i <= 5; $i++) {
                $book->bookItems()->create([
                    'barcode' => 'BC' . str_pad($book->id, 5, '0', STR_PAD_LEFT) . '-' . $i,
                    'format' => 'HARDCOVER',
                    'status' => 'AVAILABLE',
                    'rack_id' => rand(1, 10),
                ]);
            }
        }
    }
}