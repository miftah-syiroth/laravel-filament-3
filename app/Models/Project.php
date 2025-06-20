<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model implements HasMedia
{
  use HasUlids, SoftDeletes, InteractsWithMedia, HasTags, HasFactory;

  protected $fillable = [
    'type_id',
    'title',
    'slug',
    'excerpt',
    'content',
    'url',
    'start_date',
    'end_date',
    'status',
  ];

  protected $casts = [
    'status' => ProjectStatus::class,
    'start_date' => 'date',
    'end_date' => 'date',
  ];

  /**
   * Get the type that owns the Project
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function type(): BelongsTo
  {
    return $this->belongsTo(Type::class, 'type_id');
  }
}
