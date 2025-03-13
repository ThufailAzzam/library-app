<?php

namespace App\Filament\Resources\BookRequestResource\Pages;

use App\Filament\Resources\BookRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookRequest extends EditRecord
{
    protected static string $resource = BookRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
