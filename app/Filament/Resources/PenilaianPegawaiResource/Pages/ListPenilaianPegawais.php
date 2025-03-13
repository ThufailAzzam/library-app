<?php

namespace App\Filament\Resources\PenilaianPegawaiResource\Pages;

use App\Filament\Resources\PenilaianPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianPegawais extends ListRecords
{
    protected static string $resource = PenilaianPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
