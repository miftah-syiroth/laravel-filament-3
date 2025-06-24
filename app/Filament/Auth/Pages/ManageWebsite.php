<?php

namespace App\Filament\Auth\Pages;

use App\Enums\Role;
use App\Settings\WebsiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageWebsite extends SettingsPage
{
  protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
  protected static string $settings = WebsiteSetting::class;
  protected static ?string $navigationLabel = 'Website';
  protected static ?string $navigationGroup = 'Settings';
  protected static ?int $navigationSort = 7;

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
        Forms\Components\Section::make('')
          ->columns([
            'md' => 2,
          ])
          ->schema([
            Forms\Components\TextInput::make('site_name')
              ->maxLength(255),
            Forms\Components\TextInput::make('author')
              ->maxLength(255),
            Forms\Components\TextInput::make('title')
              ->maxLength(255),
            Forms\Components\TagsInput::make('keywords')
                ->placeholder('Enter keywords and press enter')
                ->separator(', '),
            Forms\Components\TextInput::make('description')
              ->columnSpan('full'),
            Forms\Components\FileUpload::make('logo')
              ->directory(WebsiteSetting::PATH)
              ->avatar()
              ->imageEditor()
              ->image(),
            Forms\Components\FileUpload::make('favicon')
              ->directory(WebsiteSetting::PATH)
              ->avatar()
              ->imageEditor()
              ->image(),
            Forms\Components\TextInput::make('suffix')
              ->maxLength(255),
            Forms\Components\TextInput::make('twitter')
          ])
      ]);
  }
}
