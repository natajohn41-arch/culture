<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
        Schema::defaultStringLength(191);
        
        // Forcer HTTPS en production pour éviter les avertissements de sécurité
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
        
        // Autoriser automatiquement les administrateurs à bypasser les policies/gates
        Gate::before(function ($user, $ability) {
            if (! $user) {
                return null;
            }

            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return true;
            }

            return null; // laisser les autres gates/policies décider
        });
    }
}
