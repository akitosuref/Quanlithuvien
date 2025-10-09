<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->isbn13,
            'title' => $this->faker->sentence(3),
            'subject' => $this->faker->word,
            'publication_date' => $this->faker->date(),
            'author_id' => \App\Models\Author::factory(),
        ];
    }
}