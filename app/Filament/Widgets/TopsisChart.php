<?php

namespace App\Filament\Widgets;

use App\Services\TopsisService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class TopsisChart extends ChartWidget
{
    protected ?string $heading = 'Topsis Chart';

    protected function getData(): array
    {
        $results = Cache::remember(key: 'topsis_results_chart', ttl: 3600, callback: function (): array {
            return app(abstract: TopsisService::class)->calculateTopsis();
        });

        $results = collect(value: $results)->sortBy(callback: 'rank')->values();

        return [
            'datasets' => [
                [
                    'label' => 'Preference Score',
                    'data' => $results->pluck(value: 'preference_score')->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.7)',
                    'borderColor' => 'rgba(79, 70, 229, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $results->pluck(value: 'name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}