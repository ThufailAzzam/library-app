<?php

namespace App\Filament\Resources\BorrowResource\Pages;

use App\Filament\Resources\BorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrow extends EditRecord
{
    protected static string $resource = BorrowResource::class;

    protected function canEdit(): bool
    {
        $record = $this->record;

        // Prevent editing if the status is 'returned'
        return $record->status !== 'returned';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $borrow = $this->record; // The current borrow record being edited
        $book = $borrow->bukus; // The associated book

        // If the book is being returned
        if ($data['status'] === 'returned' && $borrow->status !== 'returned') {
            // Increase the stock
            $book->stok += 1;
            $book->save();
        }

        // If the book is being borrowed again (e.g., status changed from 'returned' to 'active')
        if ($data['status'] === 'active' && $borrow->status === 'returned') {
            // Decrease the stock
            if ($book->stok > 0) {
                $book->stok -= 1;
                $book->save();
            } else {
                throw new \Exception('Buku tidak tersedia untuk dipinjam.');
            }
        }

        return $data;
    }
}