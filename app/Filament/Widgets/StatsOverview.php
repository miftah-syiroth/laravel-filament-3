<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class StatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('Member', User::count())
        ->description('Thank you for your support')
        ->descriptionIcon('heroicon-m-heart')
        ->color('success'),
      Stat::make('Comments', '100')
        ->description('50 unpublish')
        ->descriptionIcon('heroicon-m-eye-slash')
        ->color('warning'),
      Stat::make('Messages', '100')
        ->description('50 unread')
        ->descriptionIcon('heroicon-m-envelope')
        ->color('info'),
    ];
  }
}
