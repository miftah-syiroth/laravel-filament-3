<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Auth\Pages\Login;
use App\Models\User;
use SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

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
        FilamentSocialitePlugin::make()
          // (required) Add providers corresponding with providers in `config/services.php`.
          ->providers([
            // Create a provider 'gitlab' corresponding to the Socialite driver with the same name.
            Provider::make('google')
              ->label('Google')
              ->icon('fab-google')
              ->color(Color::hex('#4285F4'))
              ->outlined(false)
              ->stateless(false)
              ->scopes(['openid', 'profile', 'email'])
              ->with([]),
          ])
          ->registration(true)
          ->createUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
            $user = User::create([
              'name' => $oauthUser->getName() ?? $oauthUser->getNickname() ?? 'User',
              'email' => $oauthUser->getEmail(),
              'email_verified_at' => now(),
              'password' => null,
            ]);
            $user->assignRole('member');
            return $user;
          })
          ->userModelClass(\App\Models\User::class)
          ->socialiteUserModelClass(\App\Models\SocialiteUser::class)
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
