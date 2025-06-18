<?php

namespace App\Filament\Auth\Pages;

use Filament\Pages\Auth\Login as BaseAuthLogin;
use Filament\Actions\Action;

class Login extends BaseAuthLogin
{
    // Anda bisa menambahkan kustomisasi di sini jika diperlukan

    protected function getFormActions(): array
    {
        return [
            Action::make('home')
                ->label('Back to Home')
                ->url('/')
                ->icon('heroicon-o-home')
                ->color('gray')
                ->extraAttributes(['class' => 'w-full']),
            ...parent::getFormActions(),
        ];
    }
}
