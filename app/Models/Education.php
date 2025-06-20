<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Tags\HasTags;

class Education extends Model implements HasMedia
{
    use HasUlids, SoftDeletes, InteractsWithMedia, HasTags, HasFactory;

    protected $fillable = [
        'institution',
        'url',
        'major',
        'start_date',
        'end_date',
        'content',
    ];

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
