<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        // Safely ensure Admin accounts exist on boot
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                \Illuminate\Support\Facades\DB::table('admins')->insertOrIgnore([
                    'id'         => 1,
                    'name'       => 'Super Admin',
                    'email'      => 'admin@driveease.in',
                    'password'   => \Illuminate\Support\Facades\Hash::make('admin123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Illuminate\Support\Facades\DB::table('admins')->insertOrIgnore([
                    'id'         => 2,
                    'name'       => 'Admin User',
                    'email'      => 'admin@gmail.com',
                    'password'   => \Illuminate\Support\Facades\Hash::make('admin123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore DB connection errors gracefully
        }
    }
}
