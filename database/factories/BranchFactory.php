<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company() . ' Branch';
        return [
            'company_id' => Company::inRandomOrder()->first()?->id ?? Company::factory(),
            'name' => $name,
            'code' => strtoupper(Str::random(5)),
            'slug' => Str::slug($name),
            'is_main' => false,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'country' => 'United Arab Emirates',
            'address_line_1' => $this->faker->streetAddress(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}
