<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
class TicketFactory extends Factory
{
    protected $model = \App\Models\Ticket::class;

    public function definition()
    {
        return [
            'event_id' => \App\Models\Event::factory(), // This will create an Event as well
            'user_id' => null, // Assuming it's unsold at first
            'seat_number' => 'G' . $this->faker->unique()->numberBetween(1, 1000),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'type' => $this->faker->randomElement(['general', 'vip', 'vvip']),
            'is_used' => $this->faker->boolean,
        ];
    }
}



