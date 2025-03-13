<?php

namespace App\Filament\Exports;

use App\Models\Borrow;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BorrowExporter extends Exporter
{
    protected static ?string $model = Borrow::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('bukus_id'),
            ExportColumn::make('nama_peminjam'),
            ExportColumn::make('borrow_date'),
            ExportColumn::make('due_date'),
            ExportColumn::make('return_date'),
            ExportColumn::make('status'),
            ExportColumn::make('fine'),
            ExportColumn::make('condition'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your borrow record export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
