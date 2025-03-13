<?php

return [
    'auth' => [
        'guard' => 'web', // Pastikan ini sesuai dengan guard di config/auth.php
        'pages' => [
            'login' => \Filament\Pages\Auth\Login::class,
        ],
    ],

    'default_avatar_provider' => \Filament\AvatarProviders\UiAvatarsProvider::class,

    'dark_mode' => true, // Jika ingin mengaktifkan mode gelap

    'brand' => [
        'name' => env('APP_NAME', 'Filament'),
        'logo' => env('FILAMENT_LOGO', null),
    ],

    'panels' => [
        'admin' => [
            'id' => 'admin', // ID panel admin
            'path' => 'admin', // URL untuk akses Filament
            'middleware' => ['web'], // Pastikan sesuai dengan guard
            'auth' => [
                'guard' => 'web',
                'login' => \Filament\Pages\Auth\Login::class,
            ],
        ],
    ],
];
