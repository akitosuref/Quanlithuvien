<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = \App\Models\Notification::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'lending_id' => null,
            'subject' => $this->faker->sentence(6),
            'content' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['EMAIL', 'POSTAL']),
            'sent_at' => $this->faker->optional()->dateTimeThisYear,
        ];
    }
}