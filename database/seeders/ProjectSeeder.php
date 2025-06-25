<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 5 tipe
        $types = Type::factory(5)->create();

        // Buat beberapa tag yang umum digunakan
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
        ];

        foreach ($tags as $tagName) {
            Tag::findOrCreate($tagName);
        }

        // Buat 20 project
        Project::factory(16)->create()->each(function ($project) use ($types, $tags) {
            // Assign random category
            $project->update([
                'type_id' => $types->random()->id
            ]);

            // Assign random tags (2-4 tags per article)
            $randomTags = collect($tags)->random(rand(2, 4));
            $project->syncTags($randomTags);

            // Note: Untuk images, Anda bisa menambahkan file gambar ke storage/app/public
            // dan menggunakan addMedia() method, atau menggunakan service seperti Picsum
            // jika ada koneksi internet. Untuk saat ini, kita skip images untuk menghindari error.

            // Contoh jika ingin menambahkan images (uncomment jika diperlukan):
            $imageCount = rand(1, 3);
            for ($i = 0; $i < $imageCount; $i++) {
                // Pastikan ada file gambar di storage/app/public/sample-images/
                // atau gunakan URL eksternal jika ada koneksi internet
                $imageUrl = "https://picsum.photos/800/600?random=" . rand(1, 1000);

                try {
                    $project->addMediaFromUrl($imageUrl)
                        ->toMediaCollection('project-images');
                } catch (\Exception $e) {
                    // Skip jika gagal menambahkan image
                }
            }
        });
    }
}
