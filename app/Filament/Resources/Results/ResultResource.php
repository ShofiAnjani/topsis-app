<?php 
 
namespace App\Filament\Resources\Results; 
 
use BackedEnum; 
use App\Models\Result; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Filament\Tables\Table; 
use Filament\Actions\Action; 
use Filament\Schemas\Schema; 
use Filament\Actions\EditAction; 
use Filament\Resources\Resource; 
use Filament\Actions\DeleteAction; 
use Filament\Support\Icons\Heroicon; 
use Filament\Actions\BulkActionGroup; 
use Filament\Actions\DeleteBulkAction; 
use Filament\Tables\Columns\TextColumn; 
use Filament\Forms\Components\TextInput; 
use App\Filament\Resources\Results\Pages\ManageResults; 
 
class ResultResource extends Resource 
{ 
    protected static ?string $model = Result::class; 
 
    protected static ?string $navigationLabel = 'Hasil'; 
 
    protected static ?string $modelLabel = 'Hasil'; 
    protected static ?string $pluralModelLabel = 'Data Hasil'; 
 
    protected static ?int $navigationSort = 5; 
 
 
    protected static string|BackedEnum|null $navigationIcon = 
Heroicon::OutlinedChartBarSquare; 
 
    protected static ?string $recordTitleAttribute = 'result'; 
 
    public static function form(Schema $schema): Schema 
    { 
        return $schema 
            ->components([ 
                TextInput::make('result') 
                    ->required() 
                    ->maxLength(255), 
            ]); 
    } 
 
    public static function table(Table $table): Table 
    { 
        return $table 
            ->recordTitleAttribute('result') 
            ->columns([ 
                TextColumn::make('alternative.name') 
                    ->label('Nama') 
                    ->searchable(), 
                TextColumn::make('preference_score') 
                    ->label('Skor Akhir') 
                    ->formatStateUsing(fn ($state) => number_format($state, 4)), 
                TextColumn::make('rank') 
                    ->label('Rank'), 
            ]) 
            ->filters([ 
                // 
            ]) 
            ->recordActions([ 
 
                //EditAction::make(), 
                //DeleteAction::make(), 
            ]) 
            ->toolbarActions([ 
                BulkActionGroup::make([ 
                    DeleteBulkAction::make(), 
                ]), 
                Action::make('export_pdf') 
                    ->label('Export PDF') 
                    ->icon('heroicon-o-document-text') // icon stabil 
                    ->action(function () { 
                        $results = Result::with('alternative')->get(); 
                        $pdf = Pdf::loadView('pdf.results-pdf', [ 
                            'results' => $results 
                        ]); 
                        return response()->streamDownload( 
                            fn() => print($pdf->output()), 
                            'Hasil-TOPSIS.pdf' 
                        ); 
                    }), 
            ]); 
    } 
 
    public static function getPages(): array 
    { 
        return [ 
            'index' => ManageResults::route('/'), 
        ]; 
    } 
} 
 
 