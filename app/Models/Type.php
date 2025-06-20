<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'slug'
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
