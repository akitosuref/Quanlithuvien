<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            'Paulo Coelho',
            'Dale Carnegie',
            'Baird T. Spalding',
            'Tô Hoài',
            'Rosie Nguyễn',
            'Mario Puzo',
            'Hector Malot',
            'Kuroyanagi Tetsuko',
            'Nguyên Phong',
            'J.K. Rowling',
        ];

        $authorIds = [];
        foreach ($authors as $name) {
            $author = Author::firstOrCreate(['name' => $name]);
            $authorIds[$name] = $author->id;
        }

        $books = [
            [
                'title' => 'Nhà Giả Kim',
                'author_id' => $authorIds['Paulo Coelho'],
                'isbn' => '9786049680024',
                'published_date' => '2018-03-26',
                'quantity' => 10,
                'available' => 10,
            ],
            [
                'title' => 'Đắc Nhân Tâm',
                'author_id' => $authorIds['Dale Carnegie'],
                'isbn' => '8935244801084',
                'published_date' => '2017-01-01',
                'quantity' => 15,
                'available' => 15,
            ],
            [
                'title' => 'Hành Trình Về Phương Đông',
                'author_id' => $authorIds['Baird T. Spalding'],
                'isbn' => '8935086829913',
                'published_date' => '2016-01-01',
                'quantity' => 5,
                'available' => 5,
            ],
            [
                'title' => 'Dế Mèn Phiêu Lưu Ký',
                'author_id' => $authorIds['Tô Hoài'],
                'isbn' => '9786042091232',
                'published_date' => '2015-05-10',
                'quantity' => 8,
                'available' => 8,
            ],
            [
                'title' => 'Tuổi Trẻ Đáng Giá Bao Nhiêu',
                'author_id' => $authorIds['Rosie Nguyễn'],
                'isbn' => '9786047739215',
                'published_date' => '2016-08-15',
                'quantity' => 12,
                'available' => 12,
            ],
            [
                'title' => 'Bố Già',
                'author_id' => $authorIds['Mario Puzo'],
                'isbn' => '9786049690262',
                'published_date' => '2019-09-20',
                'quantity' => 7,
                'available' => 7,
            ],
            [
                'title' => 'Không Gia Đình',
                'author_id' => $authorIds['Hector Malot'],
                'isbn' => '9786042091233',
                'published_date' => '2014-02-12',
                'quantity' => 6,
                'available' => 6,
            ],
            [
                'title' => 'Totto-chan Bên Cửa Sổ',
                'author_id' => $authorIds['Kuroyanagi Tetsuko'],
                'isbn' => '9786042091234',
                'published_date' => '2013-11-30',
                'quantity' => 9,
                'available' => 9,
            ],
            [
                'title' => 'Muôn Kiếp Nhân Sinh',
                'author_id' => $authorIds['Nguyên Phong'],
                'isbn' => '9786049690263',
                'published_date' => '2020-06-01',
                'quantity' => 11,
                'available' => 11,
            ],
            [
                'title' => 'Harry Potter và Hòn Đá Phù Thủy',
                'author_id' => $authorIds['J.K. Rowling'],
                'isbn' => '9786042091235',
                'published_date' => '2003-07-21',
                'quantity' => 20,
                'available' => 20,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}