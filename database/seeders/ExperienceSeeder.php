<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'web development',
            'mobile development',
            'backend development',
            'frontend development',
            'fullstack development',
            'software development',
            'web design',
            'mobile design',
            'ui/ux design',
            'graphic design',
            'product design',
            'devops',
            'system administrator',
            'network administrator',
            'database administrator',
            'security analyst',
            'data analyst',
            'data scientist',
            'machine learning engineer',
            'artificial intelligence engineer',
            'blockchain developer',
            'blockchain engineer',
            'blockchain architect',
            'blockchain developer',
            'blockchain engineer',
            'blockchain architect'
        ];

        foreach ($tags as $tagName) {
            Tag::findOrCreate($tagName);
        }

        Experience::factory(10)->create()->each(function ($experience) use ($tags) {
            $randomTags = collect($tags)->random(rand(2, 5));
            $experience->syncTags($randomTags);

            // Tambahkan logo (satu gambar per education)
            $logoUrl = "https://picsum.photos/200/200?random=" . rand(1, 1000);

            try {
                $experience->addMediaFromUrl($logoUrl)
                    ->toMediaCollection('experience-logos');
            } catch (\Exception $e) {
                Log::error('Error adding logo: ' . $e->getMessage());
                // Skip jika gagal menambahkan logo
            }

            // Tambahkan images (1-3 gambar per education)
            $imageCount = rand(1, 3);
            for ($i = 0; $i < $imageCount; $i++) {
                // Pastikan ada file gambar di storage/app/public/sample-images/
                // atau gunakan URL eksternal jika ada koneksi internet
                $imageUrl = "https://picsum.photos/800/600?random=" . rand(1, 1000);

                try {
                    $experience->addMediaFromUrl($imageUrl)
                        ->toMediaCollection('experience-images');
                } catch (\Exception $e) {
                    Log::error('Error adding media: ' . $e->getMessage());
                    // Skip jika gagal menambahkan image
                }
            }
        });
    }
}
