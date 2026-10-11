<?php

namespace App\Providers;

use App\Mail\GmailApiTransport;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
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
        Mail::extend('gmail_api', function (array $config): GmailApiTransport {
            return new GmailApiTransport(
                $config['client_id'] ?? '',
                $config['client_secret'] ?? '',
                $config['refresh_token'] ?? '',
            );
        });

        RateLimiter::for('password-recovery', function (Request $request) {
            return Limit::perMinute(6)
                ->by($request->ip().'|'.$request->route()?->getName());
        });
    }
}
