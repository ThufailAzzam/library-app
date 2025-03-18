<?php

namespace App\Filament\Peserta\Widgets;

use App\Models\KategoriBuku;
use App\Models\BookModel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GenreBubbleChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Popularitas Genre Buku';
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get all categories with their book counts
        $categories = KategoriBuku::withCount('books')
            ->orderBy('books_count', 'desc')
            ->get();

        // Prepare data for bar chart
        $labels = $categories->pluck('nama_kategori')->toArray();
        $bookCounts = $categories->pluck('books_count')->toArray();
        
        // Generate different colors for each category
        $colors = $this->generateColors(count($categories));
        
        // Create the dataset
        $datasets = [
            [
                'label' => 'Jumlah Buku',
                'data' => $bookCounts,
                'backgroundColor' => $colors,
                'borderColor' => array_map(function($color) {
                    return str_replace('0.7', '1', $color); // Make border colors solid
                }, $colors),
                'borderWidth' => 1,
            ],
        ];

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Helper function to generate colors
    protected function generateColors(int $count): array
    {
        $colors = [];
        
        for ($i = 0; $i < $count; $i++) {
            $hue = ($i * 360 / $count) % 360;
            $colors[] = "hsla({$hue}, 70%, 60%, 0.7)";
        }
        
        return $colors;
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'x', // Horizontal bar chart for better readability with many categories
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, // Show only integer values
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Buku',
                    ],
                ],
                'y' => [
                    'ticks' => [
                        'autoSkip' => false,
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return context.dataset.label + ': ' + context.raw + ' buku';
                        }",
                    ],
                ],
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Popularitas Genre Berdasarkan Jumlah Buku',
                    'font' => [
                        'size' => 16,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    protected function getHeight(): ?string
    {
        return '400px';
    }
}