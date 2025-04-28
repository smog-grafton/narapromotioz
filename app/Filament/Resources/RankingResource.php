<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RankingResource\Pages;
use App\Filament\Resources\RankingResource\RelationManagers;
use App\Models\Ranking;
use App\Models\Fighter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Collection;

class RankingResource extends Resource
{
    protected static ?string $model = Ranking::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    
    protected static ?string $navigationGroup = 'Boxing Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ranking Details')
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                Select::make('organization')
                                    ->options([
                                        'WBC' => 'World Boxing Council (WBC)',
                                        'WBA' => 'World Boxing Association (WBA)',
                                        'IBF' => 'International Boxing Federation (IBF)',
                                        'WBO' => 'World Boxing Organization (WBO)',
                                        'IBO' => 'International Boxing Organization (IBO)',
                                        'Ring' => 'The Ring Magazine',
                                        'ESPN' => 'ESPN Rankings',
                                        'TBRB' => 'Transnational Boxing Rankings Board',
                                        'BoxRec' => 'BoxRec',
                                        'P4P' => 'Pound-for-Pound',
                                        'Nara' => 'Nara Promotionz Official Rankings',
                                    ])
                                    ->required()
                                    ->searchable(),
                                
                                Select::make('weight_class')
                                    ->options([
                                        'Heavyweight' => 'Heavyweight',
                                        'Cruiserweight' => 'Cruiserweight',
                                        'Light Heavyweight' => 'Light Heavyweight',
                                        'Super Middleweight' => 'Super Middleweight',
                                        'Middleweight' => 'Middleweight',
                                        'Super Welterweight' => 'Super Welterweight',
                                        'Welterweight' => 'Welterweight',
                                        'Super Lightweight' => 'Super Lightweight',
                                        'Lightweight' => 'Lightweight',
                                        'Super Featherweight' => 'Super Featherweight',
                                        'Featherweight' => 'Featherweight',
                                        'Super Bantamweight' => 'Super Bantamweight',
                                        'Bantamweight' => 'Bantamweight',
                                        'Super Flyweight' => 'Super Flyweight',
                                        'Flyweight' => 'Flyweight',
                                        'Light Flyweight' => 'Light Flyweight',
                                        'Minimumweight' => 'Minimumweight',
                                        'P4P' => 'Pound-for-Pound',
                                    ])
                                    ->required()
                                    ->searchable(),
                                
                                DatePicker::make('date')
                                    ->label('Ranking Date')
                                    ->default(now())
                                    ->required(),
                            ]),
                    ]),
                
                Section::make('Fighter Positions')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('fighter1_id')
                                    ->label('Position #1')
                                    ->relationship('fighter1', 'full_name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                
                                TextInput::make('fighter1_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->required(),
                                
                                Select::make('fighter2_id')
                                    ->label('Position #2')
                                    ->relationship('fighter2', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter2_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter2_id')),
                                
                                Select::make('fighter3_id')
                                    ->label('Position #3')
                                    ->relationship('fighter3', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter3_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter3_id')),
                                
                                Select::make('fighter4_id')
                                    ->label('Position #4')
                                    ->relationship('fighter4', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter4_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter4_id')),
                                
                                Select::make('fighter5_id')
                                    ->label('Position #5')
                                    ->relationship('fighter5', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter5_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter5_id')),
                                
                                Select::make('fighter6_id')
                                    ->label('Position #6')
                                    ->relationship('fighter6', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter6_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter6_id')),
                                
                                Select::make('fighter7_id')
                                    ->label('Position #7')
                                    ->relationship('fighter7', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter7_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter7_id')),
                                
                                Select::make('fighter8_id')
                                    ->label('Position #8')
                                    ->relationship('fighter8', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter8_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter8_id')),
                                
                                Select::make('fighter9_id')
                                    ->label('Position #9')
                                    ->relationship('fighter9', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter9_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter9_id')),
                                
                                Select::make('fighter10_id')
                                    ->label('Position #10')
                                    ->relationship('fighter10', 'full_name')
                                    ->searchable()
                                    ->preload(),
                                
                                TextInput::make('fighter10_previous')
                                    ->label('Previous Position')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(15)
                                    ->helperText('0 means previously unranked')
                                    ->default(0)
                                    ->visible(fn (callable $get): bool => (bool)$get('fighter10_id')),
                            ]),
                    ]),
                    
                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->placeholder('Enter any additional notes, explanations or context for this ranking')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        TextInput::make('champion_id')
                            ->label('Champion')
                            ->relationship('champion', 'full_name')
                            ->searchable()
                            ->preload()
                            ->helperText('Leave empty if there is no recognized champion in this division'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organization')
                    ->badge()
                    ->colors([
                        'danger' => 'WBC',
                        'success' => 'WBA',
                        'warning' => 'IBF',
                        'primary' => 'WBO',
                        'gray' => 'IBO',
                        'purple' => 'Ring',
                        'sky' => fn ($state): bool => in_array($state, [
                            'ESPN',
                            'TBRB',
                            'BoxRec',
                        ]),
                        'pink' => 'P4P',
                        'blue' => 'Nara',
                    ])
                    ->sortable(),
                    
                TextColumn::make('weight_class')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable(),
                    
                TextColumn::make('fighter1.full_name')
                    ->label('Top Ranked')
                    ->searchable(),
                    
                TextColumn::make('champion.full_name')
                    ->label('Champion')
                    ->searchable(),
                    
                TextColumn::make('fighterCount')
                    ->label('# of Fighters')
                    ->getStateUsing(function ($record): int {
                        $count = 0;
                        for ($i = 1; $i <= 10; $i++) {
                            $field = "fighter{$i}_id";
                            if ($record->$field) {
                                $count++;
                            }
                        }
                        return $count;
                    }),
            ])
            ->filters([
                SelectFilter::make('organization')
                    ->options([
                        'WBC' => 'World Boxing Council (WBC)',
                        'WBA' => 'World Boxing Association (WBA)',
                        'IBF' => 'International Boxing Federation (IBF)',
                        'WBO' => 'World Boxing Organization (WBO)',
                        'IBO' => 'International Boxing Organization (IBO)',
                        'Ring' => 'The Ring Magazine',
                        'ESPN' => 'ESPN Rankings',
                        'TBRB' => 'Transnational Boxing Rankings Board',
                        'BoxRec' => 'BoxRec',
                        'P4P' => 'Pound-for-Pound',
                        'Nara' => 'Nara Promotionz Official Rankings',
                    ]),
                    
                SelectFilter::make('weight_class')
                    ->options([
                        'Heavyweight' => 'Heavyweight',
                        'Cruiserweight' => 'Cruiserweight',
                        'Light Heavyweight' => 'Light Heavyweight',
                        'Super Middleweight' => 'Super Middleweight',
                        'Middleweight' => 'Middleweight',
                        'Super Welterweight' => 'Super Welterweight',
                        'Welterweight' => 'Welterweight',
                        'Super Lightweight' => 'Super Lightweight',
                        'Lightweight' => 'Lightweight',
                        'Super Featherweight' => 'Super Featherweight',
                        'Featherweight' => 'Featherweight',
                        'Super Bantamweight' => 'Super Bantamweight',
                        'Bantamweight' => 'Bantamweight',
                        'Super Flyweight' => 'Super Flyweight',
                        'Flyweight' => 'Flyweight',
                        'Light Flyweight' => 'Light Flyweight',
                        'Minimumweight' => 'Minimumweight',
                        'P4P' => 'Pound-for-Pound',
                    ]),
                    
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
                
                Filter::make('has_champion')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('champion_id'))
                    ->label('Has Champion'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view')
                    ->label('View Rankings')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Ranking $record): string => route('rankings.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRankings::route('/'),
            'create' => Pages\CreateRanking::route('/create'),
            'edit' => Pages\EditRanking::route('/{record}/edit'),
        ];
    }    
}