<?php

namespace App\Settings;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelSettings\Settings;

class WebsiteSettings extends Settings
{
    const PATH = 'settings';
    
    public string $site_name;
    public string $site_description;
    public string $author;
    public string $footer_text;
    public ?string $logo;
    public ?string $favicon;
    public ?string $contact_email;
    public ?string $contact_phone;
    public ?string $address;

    public static function group(): string
    {
        return 'website';
    }

    // get the logo path
    public function getLogoPath(): string
    {
        return asset('storage/' . $this->logo);
    }

    // delete logo
    public function deleteLogo(): void
    {
        if ($this->logo) {
            Storage::delete('storage/' . $this->logo);
        }
    }

    // delete favicon
    public function deleteFavicon(): void
    {
        if ($this->favicon) {
            Storage::delete('storage/' . $this->favicon);
        }
    }

    // handle logo state changes
    public function setLogo(?string $value): void
    {
        // jika logo tidak ada atau null atau tidak sama dengan value
        if (!$this->logo || $this->logo !== $value) {
            $this->deleteLogo();
        }
        $this->logo = $value;
    }

    // handle favicon state changes
    public function setFavicon(?string $value): void
    {
        if ($this->favicon && $this->favicon !== $value) {
            $this->deleteFavicon();
        }
        $this->favicon = $value;
    }
}
