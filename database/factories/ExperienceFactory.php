<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = fake()->company();
        return [
            'company' => $company,
            'slug' => Str::slug($company),
            'address' => fake()->address(),
            'url' => fake()->url(),
            'role' => fake()->jobTitle(),
            'job_type' => fake()->randomElement(['full-time', 'part-time', 'freelance', 'internship']),
            'start_date' => fake()->dateTimeBetween('-10 years', '-5 years'),
            'end_date' => fake()->dateTimeBetween('-4 years', 'now'),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
        ];
    }
}
