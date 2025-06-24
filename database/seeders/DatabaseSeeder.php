<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Experience;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        // Call ArticleSeeder
        $this->call([
            ArticleSeeder::class,
            EducationSeeder::class,
            ExperienceSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
