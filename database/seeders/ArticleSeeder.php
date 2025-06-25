<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Buat 5 kategori
    $categories = Category::factory(5)->create();

    // Buat beberapa tag yang umum digunakan
    $tags = [
      'teknologi',
      'programming',
      'web development',
      'mobile',
      'design',
      'business',
      'startup',
      'marketing',
      'finance',
      'health',
      'lifestyle',
      'travel',
      'food',
      'sports',
      'education',
      'science',
      'art',
      'music',
      'film',
      'books'
    ];

    foreach ($tags as $tagName) {
      Tag::findOrCreate($tagName);
    }

    // Buat 50 artikel
    Article::factory(21)->create()->each(function ($article) use ($categories, $tags) {
      // Assign random category
      $article->update([
        'category_id' => $categories->random()->id
      ]);

      // Assign random tags (2-4 tags per article)
      $randomTags = collect($tags)->random(rand(2, 4));
      $article->syncTags($randomTags);

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
          $article->addMediaFromUrl($imageUrl)
            ->toMediaCollection('article-images');
        } catch (\Exception $e) {
          // Skip jika gagal menambahkan image
        }
      }
    });
  }
}
