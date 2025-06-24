<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WebsiteSetting extends Settings
{
  const PATH = 'settings';

  public ?string $site_name;
  public ?string $author;
  public ?string $title;
  public ?string $description;
  public ?string $keywords;
  public ?string $logo;
  public ?string $favicon;
  public ?string $suffix;
  public ?string $twitter;

  public static function group(): string
  {
    return 'website';
  }

  public function getLogoPath(): string
  {
      return asset('storage/' . $this->logo);
  }

  public function getFaviconPath(): string
  {
      return asset('storage/' . $this->favicon);
  }
}
