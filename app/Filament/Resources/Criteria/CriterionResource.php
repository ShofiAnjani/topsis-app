<?php 

namespace App\Filament\Resources\Criteria; 

use BackedEnum; 
use App\Models\Criterion; 
use Filament\Tables\Table; 
use Filament\Schemas\Schema; 
use Filament\Actions\EditAction; 
use Filament\Resources\Resource; 
use Filament\Actions\DeleteAction; 
use Filament\Support\Icons\Heroicon; 
use Filament\Actions\BulkActionGroup; 
use Filament\Forms\Components\Select; 
use Filament\Actions\DeleteBulkAction; 
use Filament\Tables\Columns\TextColumn; 
use Filament\Forms\Components\TextInput; 
 
use App\Filament\Resources\Criteria\Pages\ManageCriteria; 
 
 
class CriterionResource extends Resource 
{ 
    protected static ?string $model = Criterion::class; 
 
    protected static ?string $navigationLabel = 'Kriteria'; 
 
    protected static ?string $modelLabel = 'Kriteria'; 
    protected static ?string $pluralModelLabel = 'Data Kriteria'; 
 
    protected static ?int $navigationSort = 2; 
 
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale; 
 
    protected static ?string $recordTitleAttribute = 'name'; 
 
    public static function form(Schema $schema): Schema 
    { 
        return $schema 
            ->components([ 
                TextInput::make('name') 
                    ->required() 
                    ->maxLength(255), 
                Select::make('type') 
                    ->options([ 
                        'benefit' => 'Benefit', 
                        'cost' => 'Cost', 
                    ]) 
                    ->required(), 
                TextInput::make('weight') 
                    ->numeric() 
 
                    ->required() 
                    ->minValue(0), 
            ]); 
    } 
 
    public static function table(Table $table): Table 
    { 
        return $table 
            ->recordTitleAttribute('name') 
            ->columns([ 
                TextColumn::make('name')->searchable(), 
                TextColumn::make('type'), 
                TextColumn::make('weight'), 
                 
            ]) 
            ->filters([ 
                // 
            ]) 
            ->recordActions([ 
                EditAction::make(), 
                DeleteAction::make(), 
            ]) 
            ->toolbarActions([ 
                BulkActionGroup::make([ 
                    DeleteBulkAction::make(), 
                ]), 
            ]); 
    } 
 
    public static function getPages(): array 
    { 
        return [ 
            'index' => ManageCriteria::route('/'), 
        ]; 
    } 
} 