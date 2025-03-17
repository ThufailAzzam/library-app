<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class FilamentThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('barcode-scanner', resource_path('js/filament/barcode-scanner.js')),
        ]);
    }
}