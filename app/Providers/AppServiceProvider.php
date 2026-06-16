<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\ContactSetting;
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
        Paginator::useTailwind();

        // if (config('app.env') === 'local' || env('APP_URL')) {
        //     URL::forceScheme('https');
        // }

        View::composer('*', function ($view) {
            $contactSettings = ContactSetting::where('status', 1)
                ->whereIn('key', ['facebook', 'telegram', 'youtube', 'tiktok'])
                ->pluck('value', 'key')
                ->toArray();

            $view->with('contactSettings', $contactSettings);
        });
    }
}
