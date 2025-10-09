<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LibraryCardFactory extends Factory
{
    protected $model = \App\Models\LibraryCard::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'card_number' => 'LMS' . $this->faker->unique()->numerify('#####'),
            'issued_at' => $this->faker->date(),
            'is_active' => true,
        ];
    }
}