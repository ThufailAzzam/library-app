<?php

namespace App\Filament\Resources\BookRequestResource\Pages;

use App\Filament\Resources\BookRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookRequest extends CreateRecord
{
    protected static string $resource = BookRequestResource::class;
}
