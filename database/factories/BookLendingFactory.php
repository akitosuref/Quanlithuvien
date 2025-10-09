<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookLendingFactory extends Factory
{
    protected $model = \App\Models\BookLending::class;

    public function definition()
    {
        $borrowed = $this->faker->dateTimeBetween('-20 days', 'now');
        $due = (clone $borrowed)->modify('+15 days');
        return [
            'member_id' => \App\Models\User::factory(),
            'book_item_id' => \App\Models\BookItem::factory(),
            'borrowed_date' => $borrowed,
            'due_date' => $due,
            'return_date' => $this->faker->optional()->dateTimeBetween($borrowed, $due),
        ];
    }
}