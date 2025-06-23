<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AboutSetting extends Settings
{
    const PATH = 'settings';

    public ?string $description;
    public ?string $phone;
    public ?string $address;
    public ?string $github;
    public ?string $linkedin;
    public ?string $instagram;
    public ?string $avatar;

    protected $casts = [
        'instagram' => 'array',
    ];

    public static function group(): string
    {
        return 'about';
    }
}
