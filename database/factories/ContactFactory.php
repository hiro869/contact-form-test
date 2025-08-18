<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::inRandomOrder()->value('id') ?? 1,
            'first_name'  => fake()->firstName(),
            'last_name'   => fake()->lastName(),
            'gender'      => fake()->randomElement([1,2,3]),
            'email'       => fake()->unique()->safeEmail(),
            'tel'         => fake()->numerify('0#########'),
            'address'     => fake()->address(),
            'building'    => fake()->optional()->secondaryAddress(),
            'detail'      => fake()->realText(80),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
