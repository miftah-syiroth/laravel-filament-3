<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WebsiteSetting extends Settings
{

    public ?string $site_name;
    public ?string $author;
    public ?string $title;
    public ?string $description;
    public ?string $logo;
    public ?string $favicon;
    public ?string $suffix;
    public ?string $twitter;

    public static function group(): string
    {
        return 'website';
    }
}