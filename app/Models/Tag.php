<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    use HasUlids;
}
