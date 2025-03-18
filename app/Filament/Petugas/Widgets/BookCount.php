<?php

namespace App\Filament\Petugas\Widgets;

use App\Models\BookModel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Mobil;

class BookCount extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        // Get the total number of books (sum of all stok values)
        $totalBooks = BookModel::sum('stok');
        
        // Get the count of unique book titles
        $uniqueBookCount = BookModel::count();
        
        // Calculate average copies per title
        $averageCopiesPerTitle = $uniqueBookCount > 0 
            ? round($totalBooks / $uniqueBookCount, 1) 
            : 0;
        
        return [
            Stat::make('Jumlah Buku Total', $totalBooks)
                ->description("{$uniqueBookCount} Judul buku, rata-rata {$averageCopiesPerTitle} kopi per judul")
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),
            
            Stat::make('Car Count', Mobil::query()->count())
                ->description(('Mobil yang ada di dalam sistem'))
                ->color('success')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ,
        ];
    }
}