<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\KadinPanelProvider::class, // Tambahkan ini jika belum ada
    App\Providers\Filament\KeuanganPanelProvider::class,
    App\Providers\Filament\PemohonPanelProvider::class,
    App\Providers\Filament\PesertaPanelProvider::class,
    App\Providers\Filament\PetugasPanelProvider::class,
    App\Providers\Filament\SekdinPanelProvider::class,
];
