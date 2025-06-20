<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $excerpt = fake()->paragraph(1);
        $content = fake()->paragraphs(5, true);

        return [
            'type_id' => Type::inRandomOrder()->first()?->id ?? Type::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $excerpt,
            'content' => $content,
            'url' => fake()->url(),
            'start_date' => fake()->dateTimeBetween('-10 years', '-5 years'),
            'end_date' => fake()->dateTimeBetween('-4 years', 'now'),
            'status' => fake()->randomElement(ProjectStatus::cases()),
        ];
    }
}
