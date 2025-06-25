<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CheckOAuthConfig extends Command
{
    protected $signature = 'oauth:check-config';
    protected $description = 'Check OAuth configuration for production';

    public function handle()
    {
        $this->info('Checking OAuth Configuration...');
        
        // Check Google OAuth config
        $googleConfig = Config::get('services.google');
        $this->info('Google OAuth Configuration:');
        $this->table(['Key', 'Value'], [
            ['Client ID', $googleConfig['client_id'] ? 'Set' : 'Not Set'],
            ['Client Secret', $googleConfig['client_secret'] ? 'Set' : 'Not Set'],
            ['Redirect URI', $googleConfig['redirect'] ?: 'Not Set'],
        ]);
        
        // Check app config
        $this->info('App Configuration:');
        $this->table(['Key', 'Value'], [
            ['APP_URL', config('app.url')],
            ['APP_ENV', config('app.env')],
            ['APP_DEBUG', config('app.debug') ? 'True' : 'False'],
            ['Session Driver', config('session.driver')],
            ['Session Domain', config('session.domain')],
            ['Session Secure', config('session.secure') ? 'True' : 'False'],
        ]);
        
        // Check database
        $this->info('Database Check:');
        try {
            DB::connection()->getPdo();
            $this->info('✓ Database connection successful');
            
            $userCount = \App\Models\User::count();
            $socialiteUserCount = \App\Models\SocialiteUser::count();
            
            $this->table(['Table', 'Count'], [
                ['Users', $userCount],
                ['Socialite Users', $socialiteUserCount],
            ]);
        } catch (\Exception $e) {
            $this->error('✗ Database connection failed: ' . $e->getMessage());
        }
        
        // Check roles
        $this->info('Roles Check:');
        try {
            $roles = \Spatie\Permission\Models\Role::all();
            $this->table(['Role Name', 'Guard'], $roles->map(function ($role) {
                return [$role->name, $role->guard_name];
            })->toArray());
        } catch (\Exception $e) {
            $this->error('✗ Roles check failed: ' . $e->getMessage());
        }
        
        $this->info('Configuration check completed!');
    }
} 