<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $categoryData = ['general', 'business', 'entertainment', 'health', 'science', 'sports', 'technology']; 
        
        foreach ($categoryData as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }

        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => '0fdf285d543a4b11ac347177c5112635',
            'country' => 'us'
        ]);

        // get articles property from response body
        $articles = $response->json()['articles'];

        $categories = Category::get();

        // create articles
        foreach ($articles as $article) {
            // Skip if required fields are missing
            if (empty($article['title']) || empty($article['description'])) {
                continue;
            }

            $articleData = [
                'category_id' => $categories->random()->id,
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'excerpt' => $article['description'],
                'content' => $article['content'] ?? $article['description'],
                'is_published' => true,
                'published_at' => now(),
            ];

            $createdArticle = Article::create($articleData);
        }
    }

    //   public function run(): void
    //   {
    //     // Buat 5 kategori
    //     $categories = Category::factory(5)->create();

    //     // Buat beberapa tag yang umum digunakan
    //     $tags = [
    //       'teknologi',
    //       'programming',
    //       'web development',
    //       'mobile',
    //       'design',
    //       'business',
    //       'startup',
    //       'marketing',
    //       'finance',
    //       'health',
    //       'lifestyle',
    //       'travel',
    //       'food',
    //       'sports',
    //       'education',
    //       'science',
    //       'art',
    //       'music',
    //       'film',
    //       'books'
    //     ];

    //     foreach ($tags as $tagName) {
    //       Tag::findOrCreate($tagName);
    //     }

    //     // Buat 50 artikel
    //     Article::factory(21)->create()->each(function ($article) use ($categories, $tags) {
    //       // Assign random category
    //       $article->update([
    //         'category_id' => $categories->random()->id
    //       ]);

    //       // Assign random tags (2-4 tags per article)
    //       $randomTags = collect($tags)->random(rand(2, 4));
    //       $article->syncTags($randomTags);

    //       // Note: Untuk images, Anda bisa menambahkan file gambar ke storage/app/public
    //       // dan menggunakan addMedia() method, atau menggunakan service seperti Picsum
    //       // jika ada koneksi internet. Untuk saat ini, kita skip images untuk menghindari error.

    //       // Contoh jika ingin menambahkan images (uncomment jika diperlukan):
    //       $imageCount = rand(1, 3);
    //       for ($i = 0; $i < $imageCount; $i++) {
    //         // Pastikan ada file gambar di storage/app/public/sample-images/
    //         // atau gunakan URL eksternal jika ada koneksi internet
    //         $imageUrl = "https://picsum.photos/800/600?random=" . rand(1, 1000);

    //         try {
    //           $article->addMediaFromUrl($imageUrl)
    //             ->toMediaCollection('article-images');
    //         } catch (\Exception $e) {
    //           // Skip jika gagal menambahkan image
    //         }
    //       }
    //     });
    //   }


}
