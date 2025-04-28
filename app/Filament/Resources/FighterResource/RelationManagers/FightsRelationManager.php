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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use App\Models\Fighter;
use App\Models\Fight;
use App\Models\Event;

class FightsRelationManager extends RelationManager
{
    protected static string $relationship = 'fights';

    public function form(Form $form): Form
    {
        $record = $this->getOwnerRecord();
        
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Select::make('event_id')
                            ->label('Event')
                            ->relationship('event', 'name')
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                DatePicker::make('date')
                                    ->required(),
                                TextInput::make('venue')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('location')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->required(),
                            
                        Select::make('opponent_id')
                            ->label('Opponent')
                            ->options(function () use ($record) {
                                return Fighter::where('id', '!=', $record->id)
                                    ->get()
                                    ->pluck('full_name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        DatePicker::make('date')
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
                            ])
                            ->required(),
                            
                        Select::make('result')
                            ->options([
                                'win' => 'Win',
                                'loss' => 'Loss',
                                'draw' => 'Draw',
                                'no_contest' => 'No Contest',
                                'upcoming' => 'Upcoming',
                            ])
                            ->required(),
                            
                        Select::make('method')
                            ->options([
                                'ko' => 'KO',
                                'tko' => 'TKO',
                                'unanimous_decision' => 'Unanimous Decision',
                                'split_decision' => 'Split Decision',
                                'majority_decision' => 'Majority Decision',
                                'unanimous_draw' => 'Unanimous Draw',
                                'split_draw' => 'Split Draw',
                                'majority_draw' => 'Majority Draw',
                                'disqualification' => 'Disqualification',
                                'no_contest' => 'No Contest',
                                'upcoming' => 'Upcoming',
                            ])
                            ->required(),
                            
                        TextInput::make('rounds')
                            ->label('Rounds')
                            ->numeric()
                            ->default(12)
                            ->minValue(1)
                            ->maxValue(12)
                            ->required(),
                            
                        TextInput::make('round_stopped')
                            ->label('Round Stopped')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(12)
                            ->requiredWith('method', ['ko', 'tko', 'disqualification']),
                            
                        Toggle::make('is_title_fight')
                            ->label('Title Fight'),
                            
                        TextInput::make('title')
                            ->label('Championship Title')
                            ->visible(fn (callable $get): bool => $get('is_title_fight'))
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('opponent.full_name')
                    ->label('Opponent')
                    ->searchable(),
                TextColumn::make('event.name')
                    ->label('Event')
                    ->searchable(),
                TextColumn::make('result')
                    ->badge()
                    ->colors([
                        'success' => 'win',
                        'danger' => 'loss',
                        'warning' => 'draw',
                        'gray' => fn ($state): bool => in_array($state, [
                            'no_contest',
                            'upcoming',
                        ]),
                    ]),
                TextColumn::make('method')
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'ko' => 'KO',
                            'tko' => 'TKO',
                            'unanimous_decision' => 'UD',
                            'split_decision' => 'SD',
                            'majority_decision' => 'MD',
                            'unanimous_draw' => 'Draw (UD)',
                            'split_draw' => 'Draw (SD)',
                            'majority_draw' => 'Draw (MD)',
                            'disqualification' => 'DQ',
                            'no_contest' => 'NC',
                            'upcoming' => 'Scheduled',
                            default => $state,
                        };
                    }),
                TextColumn::make('roundsDisplay')
                    ->label('Rounds')
                    ->formatStateUsing(function ($record): string {
                        if ($record->result === 'upcoming') {
                            return "{$record->rounds} (scheduled)";
                        }
                        
                        if (in_array($record->method, ['ko', 'tko', 'disqualification']) && $record->round_stopped) {
                            return "R{$record->round_stopped}/{$record->rounds}";
                        }
                        
                        return "{$record->rounds}";
                    }),
                IconColumn::make('is_title_fight')
                    ->boolean()
                    ->label('Title Fight'),
                TextColumn::make('title')
                    ->visible(fn ($record): bool => $record->is_title_fight)
                    ->limit(20),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->options([
                        'win' => 'Wins',
                        'loss' => 'Losses',
                        'draw' => 'Draws',
                        'no_contest' => 'No Contest',
                        'upcoming' => 'Upcoming',
                    ]),
                Tables\Filters\SelectFilter::make('method')
                    ->options([
                        'ko' => 'KO',
                        'tko' => 'TKO',
                        'unanimous_decision' => 'Unanimous Decision',
                        'split_decision' => 'Split Decision',
                        'majority_decision' => 'Majority Decision',
                    ]),
                Tables\Filters\Filter::make('is_title_fight')
                    ->query(fn (Builder $query): Builder => $query->where('is_title_fight', true))
                    ->label('Title Fights Only'),
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