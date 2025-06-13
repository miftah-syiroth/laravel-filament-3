<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'content',
        'reply',
        'is_approved',
        'commentable_id',
        'commentable_type',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
