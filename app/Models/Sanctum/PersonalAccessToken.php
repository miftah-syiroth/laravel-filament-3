<?php

namespace App\Models\Sanctum;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
 
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasUlids;
}
