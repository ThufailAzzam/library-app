<?php

namespace App\Filament\Resources\PopularitasResource\Pages;

use App\Filament\Resources\PopularitasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPopularitas extends ListRecords
{
    protected static string $resource = PopularitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Add header actions if needed
        ];
    }
}