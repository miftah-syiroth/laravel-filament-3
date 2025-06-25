<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Auth\Pages\Login;
use SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin;
// use App\Settings\WebsiteSetting;

class AuthPanelProvider extends PanelProvider
{
  public function panel(Panel $panel): Panel
  {
    return $panel
      ->default()
      ->id('auth')
      ->path('/auth')
      ->colors([
        'primary' => Color::Amber,
      ])
      ->brandName('Syiroth App')
      // ->brandLogo(app(WebsiteSetting::class)->getLogoPath())
      ->topNavigation()
      ->discoverResources(in: app_path('Filament/Auth/Resources'), for: 'App\\Filament\\Auth\\Resources')
      ->discoverPages(in: app_path('Filament/Auth/Pages'), for: 'App\\Filament\\Auth\\Pages')
      ->pages([
        // Pages\Dashboard::class,
      ])
      ->plugins([
        FilamentSpatieRolesPermissionsPlugin::make(),
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
      ->discoverWidgets(in: app_path('Filament/Auth/Widgets'), for: 'App\\Filament\\Auth\\Widgets')
      ->widgets([
        // Widgets\AccountWidget::class,
        // Widgets\FilamentInfoWidget::class,
      ])

      ->login(Login::class)
      ->profile(isSimple: false)
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
        Authenticate::class,
      ]);
  }
}
