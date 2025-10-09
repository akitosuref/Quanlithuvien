<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RackFactory extends Factory
{
    protected $model = \App\Models\Rack::class;

    public function definition()
    {
        return [
            'rack_number' => $this->faker->unique()->bothify('RACK-###'),
            'location_identifier' => $this->faker->bothify('Aisle-##-Shelf-#'),
        ];
    }
}