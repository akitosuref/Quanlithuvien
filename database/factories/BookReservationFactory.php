<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookReservationFactory extends Factory
{
    protected $model = \App\Models\BookReservation::class;

    public function definition()
    {
        $reservation = $this->faker->dateTimeBetween('-10 days', 'now');
        return [
            'member_id' => \App\Models\User::factory(),
            'book_item_id' => \App\Models\BookItem::factory(),
            'reservation_date' => $reservation,
            'status' => $this->faker->randomElement(['WAITING', 'PROCESSING', 'CANCELED']),
        ];
    }
}