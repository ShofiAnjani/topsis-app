<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Services\TopsisService;
use Illuminate\Support\Facades\Cache;
use Filament\Tables\Columns\TextColumn;

class TopsisCalculation extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Kalkulasi TOPSIS';
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.pages.topsis-calculation';

    public $results;

    public function mount(TopsisService $topsisService)
    {
        // ✅ perbaikan: hapus tanda > yang salah di baris berikut
        $this->results = Cache::remember('topsis_results', 3600, fn () => $topsisService->calculateTopsis());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns($this->getTableColumns())
            ->actions($this->getTableActions())
            ->records(fn () => collect($this->results));
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Alternative'),
            TextColumn::make('preference_score')
                ->label('Preference Score')
                ->formatStateUsing(fn ($state) => number_format($state, 4)),
            TextColumn::make('rank')->label('Rank'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('recalculate')
                ->label('Recalculate')
                ->action(function () {
                    Cache::forget('topsis_results');
                    $this->results = app(TopsisService::class)->calculateTopsis();
                })
                ->icon('heroicon-m-arrow-path'),
        ];
    }
}