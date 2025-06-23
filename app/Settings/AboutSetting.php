<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AboutSetting extends Settings
{
    const PATH = 'settings';

    public ?string $full_name;
    public ?string $description;
    public ?string $phone;
    public ?string $email;
    public ?string $address;
    public ?string $github;
    public ?string $linkedin;
    public ?string $instagram;
    public ?string $avatar;
    public ?string $skills;

    protected $casts = [
        'instagram' => 'array',
    ];

    public static function group(): string
    {
        return 'about';
    }
}
