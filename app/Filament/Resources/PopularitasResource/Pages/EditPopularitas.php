<?php

namespace App\Filament\Resources\PopularitasResource\Pages;

use App\Filament\Resources\PopularitasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPopularitas extends EditRecord
{
    protected static string $resource = PopularitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
