<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Tags\HasTags;
use RalphJSmit\Laravel\SEO\Support\HasSEO;

class Education extends Model implements HasMedia
{
    use HasUlids, HasSEO, SoftDeletes, InteractsWithMedia, HasTags, HasFactory;

    protected $fillable = [
        'institution',
        'url',
        'slug',
        'major',
        'start_date',
        'end_date',
        'content',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // append logo to the model
    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('education-logos');
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
