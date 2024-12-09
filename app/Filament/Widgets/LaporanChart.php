<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Laporan;
use Carbon\Carbon;  

class LaporanChart extends ChartWidget
{
    protected static ?string $heading = 'Laporan per month';
    protected static string $color = 'primary';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::model(Laporan::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
 
        return [
            'datasets' => [
                [
                    'label' => 'Laporan posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
                ->map(fn ($month) => Carbon::create()->month($month)->format('M')) 
                ->toArray(), 
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
