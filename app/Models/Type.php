<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Type extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'name', 'slug'
    ];
}
