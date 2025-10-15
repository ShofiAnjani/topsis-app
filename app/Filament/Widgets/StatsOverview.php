<?php

namespace App\Filament\Widgets;

use App\Models\Result;
use App\Models\Criterion;
use App\Models\Alternative;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(label: 'Total Kandidat', value: Alternative::count())
                ->description(description: 'Number of candidates')
                ->icon(icon: 'heroicon-o-user-group'),

            Stat::make(label: 'Total Kriteria', value: Criterion::count())
                ->description(description: 'Evaluation criteria')
                ->icon(icon: 'heroicon-o-scale'),

            Stat::make(label: 'Top Rank Bantuan', value: Result::orderBy('rank')->first()?->alternative->name ?? 'N/A')
                ->description(description: 'Highest preference score')
                ->icon(icon: 'heroicon-o-trophy'),
        ];
    }
}