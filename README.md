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



<?php

namespace App\Filament\Auth\Pages;

use App\Settings\WebsiteSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageWebsite extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = WebsiteSettings::class;

    protected static ?string $navigationGroup = 'Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_description')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('author')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('footer_text')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->directory(WebsiteSettings::PATH)
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->previewable(),
                        Forms\Components\FileUpload::make('favicon')
                            ->directory(WebsiteSettings::PATH)
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->previewable(),
                        Forms\Components\TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_phone')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->maxLength(255)
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
