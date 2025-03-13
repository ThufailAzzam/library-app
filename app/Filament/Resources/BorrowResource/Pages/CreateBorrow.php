<?php

namespace App\Filament\Resources\BorrowResource\Pages;

use App\Models\BookModel;
use App\Filament\Resources\BorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrow extends CreateRecord
{
    protected static string $resource = BorrowResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $book = BookModel::find($data['bukus_id']);

        // Check if the book is available
        if ($book && $book->stok > 0) {
            // Decrease the stock
            $book->stok -= 1;
            $book->save();

            // Set the status to 'active'
            $data['status'] = 'active';
        } else {
            // Throw an error if the book is not available
            throw new \Exception('Buku tidak tersedia untuk dipinjam.');
        }

        return $data;
    }
}
