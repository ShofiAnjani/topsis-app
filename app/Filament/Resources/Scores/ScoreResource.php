<?php 
namespace App\Filament\Resources\Scores; 
use BackedEnum; 
use App\Models\Score; 
use App\Models\Criterion; 
use Filament\Tables\Table; 
use App\Models\Alternative; 
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
use App\Filament\Resources\Scores\Pages\ManageScores; 
 
class ScoreResource extends Resource 
{ 
    protected static ?string $model = Score::class; 
 
    protected static ?string $navigationLabel = 'Skor'; 
 
    protected static ?int $navigationSort = 3; 
 
    protected static ?string $modelLabel = 'Skor'; 
    protected static ?string $pluralModelLabel = 'Data Skor'; 
 
    protected static string|BackedEnum|null $navigationIcon = 
Heroicon::OutlinedIdentification; 
 
    protected static ?string $recordTitleAttribute = 'alternative'; 
 
    public static function form(Schema $schema): Schema 
    { 
        return $schema 
            ->components([ 
                Select::make('alternative_id') 
                    ->options(Alternative::pluck('name', 'id')) 
                    ->required(), 
                Select::make('criterion_id') 
                    ->options(Criterion::pluck('name', 'id')) 
                    ->required(), 
                TextInput::make('value') 
                    ->numeric() 
                    ->required() 
 
                    ->minValue(0), 
            ]); 
    } 
 
    public static function table(Table $table): Table 
    { 
        return $table 
            ->recordTitleAttribute('alternative') 
            ->columns([ 
                TextColumn::make('alternative.name')->searchable(), 
                TextColumn::make('criterion.name'), 
                TextColumn::make('value'), 
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
            'index' => ManageScores::route('/'), 
        ]; 
    } 
} 