<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider as BasePanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\RedirectIfAuthenticated;
use Filament\Navigation\NavigationItem;
use SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;
// use App\Settings\WebsiteSetting;

class PanelProvider extends BasePanelProvider
{
  public function panel(Panel $panel): Panel
  {
    return $panel
      ->default()
      ->id('/')
      ->path('/')
      ->colors([
        'primary' => Color::Amber,
      ])
      ->brandName('Syiroth App')
      // ->brandLogo(app(WebsiteSetting::class)->getLogoPath())
      ->topNavigation()
      ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
      ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
      ->resources([
        // config('filament-logger.activity_resource')
      ])
      ->pages([
        // Pages\Dashboard::class,
      ])
      ->plugins([
        SimpleLightBoxPlugin::make(),
        BreezyCore::make()
          ->myProfile(
            shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
            userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
            shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
            navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
            hasAvatars: false, // Enables the avatar upload form component (default = false)
            slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
          )
      ])
      ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
      ->widgets([
        // Widgets\FilamentInfoWidget::class,
      ])
      ->navigationItems([
        NavigationItem::make('Login')
          ->url('/auth/login')
          ->icon('heroicon-o-arrow-right-on-rectangle')
          ->sort(4),
      ])
      ->renderHook(
        'panels::head.start',
        fn() => view('analyticsTag'),
      )
      ->middleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        DisableBladeIconComponents::class,
        DispatchServingFilamentEvent::class,
      ])
      ->authMiddleware([
        RedirectIfAuthenticated::class,
      ]);
  }
}
