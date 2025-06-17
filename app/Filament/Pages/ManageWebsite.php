<?php

namespace App\Filament\Pages;

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
