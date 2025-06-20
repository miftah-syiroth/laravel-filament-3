<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model implements HasMedia
{
    use HasUlids, SoftDeletes, InteractsWithMedia, HasTags, HasFactory;

    protected $fillable = [
        'company',
        'address',
        'url',
        'role',
        'job_type',
        'start_date',
        'end_date',
        'excerpt',
        'content',
    ];

    public function getPeriodAttribute()
    {
        // start data - end data, if end data is null, then return start date - now
        if ($this->end_date) {
            return $this->start_date->format('M Y') . ' - ' . $this->end_date->format('M Y');
        }
        return $this->start_date->format('M Y') . ' - now';
    }

    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('experience-logos');
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('experience-images');
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
