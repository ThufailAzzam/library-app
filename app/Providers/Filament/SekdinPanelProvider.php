<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

// Import your resources
use App\Filament\Resources\BookRequestResource;
use App\Filament\Resources\BookResource;
use App\Filament\Resources\BorrowResource;
use App\Filament\Resources\KategoriBukuResource;
use App\Filament\Resources\MobilResource;
use App\Filament\Resources\PenilaianPegawaiResource;
use App\Filament\Resources\PopularitasResource;
use App\Filament\Resources\UserResource;

class SekdinPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('sekdin')
            ->path('sekdin')
            ->login()
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Sekdin/Resources'), for: 'App\\Filament\\Sekdin\\Resources')
            ->discoverPages(in: app_path('Filament/Sekdin/Pages'), for: 'App\\Filament\\Sekdin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->resources([
                BookRequestResource::class,
                BookResource::class,
                BorrowResource::class,
                KategoriBukuResource::class,
                MobilResource::class,
                PenilaianPegawaiResource::class,
                PopularitasResource::class,
                UserResource::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Sekdin/Widgets'), for: 'App\\Filament\\Sekdin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            ])
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make());
    }
}