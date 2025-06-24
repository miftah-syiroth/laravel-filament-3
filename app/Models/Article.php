<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Article extends Model implements HasMedia
{
    use HasUlids, SoftDeletes, InteractsWithMedia, HasTags, HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('article-images');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getDynamicSEOData(): SEOData
    {
        // $pathToFeaturedImageRelativeToPublicPath = // ..;

        // Override only the properties you want:
        return new SEOData(
            title: $this->title,
            description: $this->title,
            image: $this->getFirstMediaUrl('article-images'),
        );
    }
}
