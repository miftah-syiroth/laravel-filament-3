<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists;


class AboutMe extends Page implements HasInfolists
{
    protected $user;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.about-me';


    public function mount()
    {
        $this->user = User::first();
    }

    public function aboutMeInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->user)
            ->columns(1)
            ->extraAttributes(['class' => 'w-full'])
            ->schema([
                Infolists\Components\Section::make('About Me')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Name')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope'),
                    ]),
            ]);
    }
}
