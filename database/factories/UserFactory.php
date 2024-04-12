<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => $this->faker->unique()->bothify('SCH##??###'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // default password for all users, consider a secure approach for real applications
            'role' => 'user', // default role
            'lname' => $this->faker->lastName,
            'fname' => $this->faker->firstName,
            'mname' => $this->faker->optional()->lastName,
            'birthdate' => $this->faker->date(),
            'sex' => $this->faker->randomElement(['male', 'female', 'other']),
            'contact_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            // 'fcm_token' is typically generated per device upon app installation, you might want to leave this out of seeding
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
