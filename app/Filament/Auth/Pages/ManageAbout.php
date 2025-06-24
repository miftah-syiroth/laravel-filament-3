<?php

namespace App\Filament\Auth\Pages;

use App\Settings\AboutSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use App\Enums\Role;

class ManageAbout extends SettingsPage
{
  protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
  protected static string $settings = AboutSetting::class;
  protected static ?string $navigationLabel = 'About';
  protected static ?string $navigationGroup = 'Settings';
  protected static ?int $navigationSort = 0;

  public static function canAccess(): bool
  {
    return auth()->user()->hasRole(Role::ADMIN->value);
  }

  public function getTitle(): string
  {
    return '';
  }

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Personal Information')
          ->columns()
          ->schema([
            Forms\Components\TextInput::make('full_name')
              ->maxLength(255),
            Forms\Components\TextInput::make('phone')
              ->maxLength(255),
            Forms\Components\TextInput::make('email')
              ->maxLength(255),
            Forms\Components\TextInput::make('address')
              ->maxLength(255),
            Forms\Components\TagsInput::make('skills')
              ->placeholder('Enter skills and press enter')
              ->separator(',')
              ->columnSpan('full'),
            Forms\Components\FileUpload::make('avatar')
              ->directory(AboutSetting::PATH)
              ->avatar()
              ->image()
              ->imageEditor()
              ->previewable(),
            Forms\Components\RichEditor::make('description')
              ->disableToolbarButtons([
                'attachFiles',
              ])
              ->columnSpan('full'),
          ]),
        Forms\Components\Section::make('Social Media')
          ->columns([
            'sm' => 3,
          ])
          ->schema([
            Forms\Components\TextInput::make('github')
              ->maxLength(255),
            Forms\Components\TextInput::make('linkedin')
              ->maxLength(255),
            Forms\Components\TextInput::make('instagram')
              ->maxLength(255),
          ]),
      ]);
  }
}
