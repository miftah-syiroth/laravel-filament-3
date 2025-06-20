<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'sekolah', 'pendidikan', 'tk', 'sd', 'smp', 'sma', 's1', 'sekolah dasar', 'sekolah menengah', 'sekolah menengah atas', 'universitas', 'sarjana', 'informatika', 'ponpes', 'pondok pesantren'
        ];

        foreach ($tags as $tagName) {
            Tag::findOrCreate($tagName);
        }

        Education::factory(5)->create()->each(function ($education) use ($tags) {
            $randomTags = collect($tags)->random(rand(2, 5));
            $education->syncTags($randomTags);

            // Tambahkan logo (satu gambar per education)
            $logoUrl = "https://picsum.photos/200/200?random=" . rand(1, 1000);
            
            try {
                $education->addMediaFromUrl($logoUrl)
                    ->toMediaCollection('education-logos');
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
                    $education->addMediaFromUrl($imageUrl)
                        ->toMediaCollection('education-images');
                } catch (\Exception $e) {
                    Log::error('Error adding media: ' . $e->getMessage());
                    // Skip jika gagal menambahkan image
                }
            }
        });
    }
}
