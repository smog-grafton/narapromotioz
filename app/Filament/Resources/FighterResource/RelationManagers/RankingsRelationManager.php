<?php

namespace App\Filament\Resources\FighterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use App\Models\Ranking;

class RankingsRelationManager extends RelationManager
{
    protected static string $relationship = 'rankings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
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
                            ])
                            ->required(),
                            
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
                            ->required(),
                            
                        TextInput::make('position')
                            ->label('Rank Position')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(15)
                            ->required(),
                            
                        DatePicker::make('date')
                            ->label('Ranking Date')
                            ->default(now())
                            ->required(),
                            
                        TextInput::make('previous_position')
                            ->label('Previous Position')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Enter 0 for new entries, if previously unranked'),
                            
                        TextInput::make('points')
                            ->label('Points')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Only required for certain rankings like BoxRec'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('organization')
            ->columns([
                TextColumn::make('organization')
                    ->sortable(),
                TextColumn::make('weight_class')
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Rank')
                    ->sortable(),
                TextColumn::make('previous_position')
                    ->label('Previous')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state : 'NR')
                    ->sortable(),
                TextColumn::make('movement')
                    ->label('Change')
                    ->formatStateUsing(function ($record) {
                        if (!$record->previous_position || $record->previous_position == 0) {
                            return 'NEW';
                        }
                        
                        $change = $record->previous_position - $record->position;
                        
                        if ($change > 0) {
                            return "+{$change}";
                        } elseif ($change < 0) {
                            return "{$change}";
                        } else {
                            return "⟺";
                        }
                    })
                    ->colors([
                        'success' => fn ($record): bool => $record->previous_position && $record->previous_position > $record->position,
                        'danger' => fn ($record): bool => $record->previous_position && $record->previous_position < $record->position,
                        'gray' => fn ($record): bool => !$record->previous_position || $record->previous_position == $record->position,
                    ]),
                TextColumn::make('date')
                    ->label('Updated')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('points')
                    ->label('Points')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organization')
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
                    ]),
                Tables\Filters\SelectFilter::make('weight_class')
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
                Tables\Filters\Filter::make('top_10')
                    ->query(fn (Builder $query): Builder => $query->where('position', '<=', 10))
                    ->label('Top 10 Only'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }
}