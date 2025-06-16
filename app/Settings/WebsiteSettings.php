<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WebsiteSettings extends Settings 
{
    public string $site_name;
    public string $site_description;
    public string $author;
    public string $footer_text;
    public ?string $logo;
    public ?string $favicon;
    public ?string $contact_email;
    public ?string $contact_phone;
    public ?string $address;
    public ?string $facebook_url;
    public ?string $twitter_url;
    public ?string $instagram_url;

    public static function group(): string
    {
        return 'website';
    }
}
