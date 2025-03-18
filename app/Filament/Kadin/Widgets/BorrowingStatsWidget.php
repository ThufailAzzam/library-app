<?php

namespace App\Filament\Kadin\Widgets;

use App\Models\Borrow;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BorrowingStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Peminjaman Bulanan';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get data for the last 6 months
        $data = $this->getBorrowingData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peminjaman',
                    'data' => $data['counts'],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 2,
                    'tension' => 0.3, // Adds a slight curve to the lines
                    'fill' => true, // Fill area under the line
                    'pointBackgroundColor' => 'rgb(54, 162, 235)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(54, 162, 235)',
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ]
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getBorrowingData(): array
    {
        $labels = [];
        $counts = [];
        
        // Get the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            
            $startOfMonth = Carbon::now()->subMonths($i)->startOfMonth();
            $endOfMonth = Carbon::now()->subMonths($i)->endOfMonth();
            
            // Count borrowings for this month
            $count = Borrow::query()
                ->whereBetween('borrow_date', [$startOfMonth, $endOfMonth])
                ->count();
                
            $counts[] = $count;
        }
        
        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Changed from 'bar' to 'line'
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
        ];
    }
}