<?php

namespace App\Filament\Resources\BookScannerResource\Pages;

use App\Filament\Resources\BookScannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookScanners extends ListRecords
{
    protected static string $resource = BookScannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
