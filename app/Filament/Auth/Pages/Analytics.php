<?php

namespace App\Filament\Auth\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentGoogleAnalytics\Widgets;
use App\Filament\Widgets\StatsOverview;

class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.analytics';
  
    protected function getHeaderWidgets(): array
    {
      return [
        StatsOverview::class,
        Widgets\VisitorsWidget::class,
        Widgets\SessionsWidget::class,
        Widgets\SessionsByCountryWidget::class,
        Widgets\SessionsByDeviceWidget::class,
      ];
    }
}
