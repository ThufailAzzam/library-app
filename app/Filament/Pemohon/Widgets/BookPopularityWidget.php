<?php

namespace App\Filament\Pemohon\Widgets;

use App\Models\Popularitas;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BookPopularityWidget extends ChartWidget
{
    protected static ?string $heading = 'Popularitas Buku';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $popularBooks = Popularitas::select('judul', 'jumlah_pinjam')
            ->orderBy('jumlah_pinjam', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peminjaman',
                    'data' => $popularBooks->pluck('jumlah_pinjam')->toArray(),
                    'backgroundColor' => [
                        '#36A2EB', // Blue
                        '#FF6384', // Red
                        '#FFCE56', // Yellow
                        '#4BC0C0', // Teal
                        '#9966FF', // Purple
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $popularBooks->pluck('judul')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'boxWidth' => 15,
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return context.label + ': ' + context.raw + ' peminjaman';
                        }",
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}