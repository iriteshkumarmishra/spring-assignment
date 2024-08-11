<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    // The name of the factory's corresponding model
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'age' => $this->faker->numberBetween(18, 100),
            'points' => $this->faker->numberBetween(0, 100),
            'address_id' => UserAddress::factory(), // Generates an associated address
        ];
    }
}