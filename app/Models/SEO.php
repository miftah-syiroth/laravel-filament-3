<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use RalphJSmit\Laravel\SEO\Models\SEO as SEOModel;

class SEO extends SEOModel
{
    use HasUlids, HasFactory;
}
