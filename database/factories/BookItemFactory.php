<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookItemFactory extends Factory
{
    protected $model = \App\Models\BookItem::class;

    public function definition()
    {
        return [
            'book_id' => \App\Models\Book::factory(),
            'rack_id' => \App\Models\Rack::factory(),
            'barcode' => $this->faker->unique()->ean13,
            'format' => $this->faker->randomElement(['HARDCOVER', 'PAPERBACK', 'AUDIOBOOK', 'EBOOK', 'NEWSPAPER', 'MAGAZINE', 'JOURNAL']),
            'status' => $this->faker->randomElement(['AVAILABLE', 'RESERVED', 'LOANED', 'LOST']),
        ];
    }
}