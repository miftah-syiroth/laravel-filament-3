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
use SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin;

use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

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
          // (optional) Override the panel slug to be used in the oauth routes. Defaults to the panel's configured path.
          ->slug('auth')
          // (optional) Enable/disable registration of new (socialite-) users.
          ->registration(true)
          // (optional) Enable/disable registration of new (socialite-) users using a callback.
          // In this example, a login flow can only continue if there exists a user (Authenticatable) already.
          // ->registration(fn(string $provider, SocialiteUserContract $oauthUser, ?Authenticatable $user) => (bool) $user)
          ->createUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
            $user = User::create([
              'name' => $oauthUser->getName() ?? $oauthUser->getNickname() ?? $oauthUser->getId() ?? 'Member',
              'email' => $oauthUser->getEmail(),
              'email_verified_at' => now(),
            ]);
            $user->assignRole('member');
            return $user;
          })
          ->authorizeUserUsing(function (FilamentSocialitePlugin $plugin, SocialiteUserContract $oauthUser) {
            return true;
          })
          // (optional) Change the associated model class.
          ->userModelClass(\App\Models\User::class)
          // (optional) Change the associated socialite class (see below).
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
        // Authenticate::class,
      ]);
  }
}
