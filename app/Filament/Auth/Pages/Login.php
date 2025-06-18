<?php

namespace App\Filament\Auth\Pages;

use Filament\Pages\Page;

class Login extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.auth.pages.login';
}
