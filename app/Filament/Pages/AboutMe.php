<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\User;
use App\Settings\AboutSetting;
use Filament\Pages\Page;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists;


class AboutMe extends Page implements HasInfolists
{
  protected $aboutData;

  protected static ?string $navigationIcon = 'heroicon-o-document-text';
  protected static string $view = 'filament.pages.about-me';


  public function mount(AboutSetting $aboutSetting)
  {
    $this->aboutData = $aboutSetting;
  }

  public function aboutMeInfolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->columns(1)
      ->schema([
        Infolists\Components\Split::make([
          Infolists\Components\Section::make()
            ->schema([
              Infolists\Components\TextEntry::make('full_name')
                ->state(function () {
                  return $this->aboutData->full_name;
                })
                ->icon('heroicon-o-user'),
              Infolists\Components\TextEntry::make('phone')
                ->state(function () {
                  return $this->aboutData->phone;
                })
                ->icon('heroicon-o-phone')
                ->url(function () {
                  $phone = $this->aboutData->phone;
                  // Hapus karakter non-digit dan tambahkan kode negara jika belum ada
                  $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                  if (!str_starts_with($cleanPhone, '62')) {
                    $cleanPhone = '62' . ltrim($cleanPhone, '0');
                  }
                  return "https://wa.me/{$cleanPhone}";
                })->openUrlInNewTab(),
              Infolists\Components\TextEntry::make('email')
                ->state(function () {
                  return $this->aboutData->email;
                })
                ->icon('heroicon-o-envelope')
                ->url(function () {
                  return 'mailto:' . $this->aboutData->email;
                }),
              Infolists\Components\TextEntry::make('address')
                ->state(function () {
                  return $this->aboutData->address;
                })
                ->icon('heroicon-o-map-pin')
            ])->grow(false),
          Infolists\Components\Section::make()

            ->schema([
              Infolists\Components\TextEntry::make('description')
                ->hiddenLabel()
                ->state(function () {
                  return $this->aboutData->description;
                })
                ->html()
                ->prose()
                ->markdown(),
              Infolists\Components\Split::make([
                Infolists\Components\ImageEntry::make('avatar')
                  ->hiddenLabel()
                  ->state(function () {
                    return $this->aboutData->avatar;
                  })
                  ->circular()
                  ->columnSpanFull(),
                Infolists\Components\TextEntry::make('skills')
                  ->state(function () {
                    return $this->aboutData->skills;
                  })
                  ->badge()
                  ->separator(',')
                  ->color('primary')
                  ->grow(false),
              ])->from('sm'),
              Infolists\Components\Fieldset::make('Social Media')
                ->schema([
                  Infolists\Components\TextEntry::make('github')
                    ->state(function () {
                      return $this->aboutData->github;
                    })
                    ->icon('antdesign-github')
                    ->url(function () {
                      return $this->aboutData->github;
                    })->openUrlInNewTab(),
                  Infolists\Components\TextEntry::make('linkedin')
                    ->state(function () {
                      return $this->aboutData->linkedin;
                    })
                    ->icon('antdesign-linkedin')
                    ->url(function () {
                      return $this->aboutData->linkedin;
                    })->openUrlInNewTab(),
                  Infolists\Components\TextEntry::make('instagram')
                    ->state(function () {
                      return $this->aboutData->instagram;
                    })
                    ->icon('antdesign-instagram')
                    ->url(function () {
                      return $this->aboutData->instagram;
                    })->openUrlInNewTab(),
                ])
                ->columns(3)
            ]),
        ])->from('md'),
      ]);
  }
}
