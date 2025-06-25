<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser as FilamentSocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialiteUser extends Model implements FilamentSocialiteUserContract
{
  use HasUlids, HasFactory;

  protected $fillable = [
    'user_id',
    'provider',
    'provider_id',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function getUser(): Authenticatable
  {
    return $this->user;
  }

  public static function findForProvider(string $provider, SocialiteUserContract $oauthUser): ?self
  {
    return self::where('provider', $provider)
      ->where('provider_id', $oauthUser->getId())
      ->first();
  }

  public static function createForProvider(
    string $provider,
    SocialiteUserContract $oauthUser,
    Authenticatable $user
  ): self {
    return self::create([
      'user_id' => $user->id,
      'provider' => $provider,
      'provider_id' => $oauthUser->getId(),
    ]);
  }
}
