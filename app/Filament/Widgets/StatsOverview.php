<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User; 
use App\Models\Laporan; 
use App\Models\Report; 

class StatsOverview extends BaseWidget
{   
    protected function getStats(): array
    {
        return [
            Stat::make('Total User', User::count()),
            Stat::make('Total Laporan', Laporan::count()),
            Stat::make('Total Reports', Report::count()),
        ];
    }
}
