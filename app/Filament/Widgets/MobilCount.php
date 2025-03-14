<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Mobil;

class MobilCount extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Car Count', Mobil::query()->count())
            ->description(('Mobil yang ada di dalam sistem'))
            ->color('success')
            ->descriptionIcon('heroicon-m-exclamation-circle')
            ,
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
