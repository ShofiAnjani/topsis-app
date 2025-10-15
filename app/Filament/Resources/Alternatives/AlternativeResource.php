<?php 
 
namespace App\Filament\Resources\Alternatives; 
 
use BackedEnum; 
use Filament\Tables\Table; 
use App\Models\Alternative; 
use Filament\Schemas\Schema; 
use Filament\Actions\EditAction; 
use Filament\Resources\Resource; 
use Filament\Actions\CreateAction; 
use Filament\Actions\DeleteAction; 
use Filament\Support\Icons\Heroicon; 
use Filament\Actions\BulkActionGroup; 
use Filament\Actions\DeleteBulkAction; 
use Filament\Tables\Columns\TextColumn; 
use Filament\Forms\Components\TextInput; 
use App\Filament\Resources\Alternatives\Pages\ManageAlternatives; 
 
class AlternativeResource extends Resource 
{ 
    protected static ?string $model = Alternative::class; 
 
    protected static ?string $navigationLabel = 'Data Kandidat'; 
 
    protected static ?string $modelLabel = 'Kandidat'; 
    protected static ?string $pluralModelLabel = 'Data Kandidat'; 
 
    protected static ?int $navigationSort = 1; 
 
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers; 
 
 
    protected static ?string $recordTitleAttribute = 'Kandidat'; 
 
 
    public static function form(Schema $schema): Schema 
    { 
        return $schema 
            ->components([ 
                TextInput::make('name') 
                    ->required() 
                    ->maxLength(255), 
                TextInput::make('address') 
                    ->maxLength(255), 
            ]); 
    } 
 
    public static function table(Table $table): Table 
    { 
        return $table 
            ->recordTitleAttribute('name') 
            ->columns([ 
                TextColumn::make('name') 
                ->searchable(), 
                TextColumn::make('address'), 
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
        'index' => ManageAlternatives::route('/'), 
    ]; 
  } 
} 