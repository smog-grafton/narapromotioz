<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FightResource\Pages;
use App\Models\Fight;
use App\Models\Fighter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;

class FightResource extends Resource
{
    protected static ?string $model = Fight::class;

    protected static ?string $navigationIcon = 'heroicon-o-spark';
    
    protected static ?string $navigationGroup = 'Boxing Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Select::make('event_id')
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('fighter1_id')
                            ->relationship('fighter1', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Fighter 1'),
                        
                        Select::make('fighter2_id')
                            ->relationship('fighter2', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Fighter 2'),
                        
                        Select::make('weight_class')
                            ->options([
                                'Heavyweight' => 'Heavyweight',
                                'Cruiserweight' => 'Cruiserweight',
                                'Light Heavyweight' => 'Light Heavyweight',
                                'Super Middleweight' => 'Super Middleweight',
                                'Middleweight' => 'Middleweight',
                                'Welterweight' => 'Welterweight',
                                'Lightweight' => 'Lightweight',
                                'Featherweight' => 'Featherweight',
                                'Bantamweight' => 'Bantamweight',
                                'Flyweight' => 'Flyweight',
                            ])
                            ->required(),
                        
                        TextInput::make('rounds')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(15)
                            ->default(12)
                            ->required(),
                        
                        Select::make('fight_order')
                            ->options(array_combine(range(1, 10), range(1, 10)))
                            ->label('Bout Order')
                            ->required()
                            ->helperText('Position on the fight card (1 = main event)'),
                    ]),
                
                Section::make('Fight Details')
                    ->schema([
                        Toggle::make('is_title_fight')
                            ->label('Title Fight')
                            ->default(false),
                        
                        Toggle::make('is_main_event')
                            ->label('Main Event')
                            ->default(false),
                        
                        TextInput::make('title')
                            ->maxLength(255)
                            ->label('Fight Title/Belt')
                            ->placeholder('e.g. WBC World Heavyweight Championship'),
                            
                        Select::make('result')
                            ->options([
                                '' => 'Not Fought Yet',
                                'fighter1_ko' => 'Fighter 1 Win by KO/TKO',
                                'fighter2_ko' => 'Fighter 2 Win by KO/TKO',
                                'fighter1_decision' => 'Fighter 1 Win by Decision',
                                'fighter2_decision' => 'Fighter 2 Win by Decision',
                                'fighter1_submission' => 'Fighter 1 Win by Submission',
                                'fighter2_submission' => 'Fighter 2 Win by Submission',
                                'draw' => 'Draw',
                                'no_contest' => 'No Contest',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default(''),
                        
                        Textarea::make('result_notes')
                            ->maxLength(500)
                            ->placeholder('Additional notes about the fight result')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('event.event_date')
                    ->label('Event Date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('matchup')
                    ->label('Fight')
                    ->formatStateUsing(fn ($record): string => "{$record->fighter1->full_name} vs {$record->fighter2->full_name}")
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('fighter1', function (Builder $query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%");
                        })->orWhereHas('fighter2', function (Builder $query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('weight_class')
                    ->sortable(),
                TextColumn::make('rounds')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => "{$state} Rounds"),
                TextColumn::make('title')
                    ->label('Title/Belt')
                    ->limit(20)
                    ->placeholder('Non-title fight'),
                IconColumn::make('is_title_fight')
                    ->boolean()
                    ->label('Title'),
                IconColumn::make('is_main_event')
                    ->boolean()
                    ->label('Main Event'),
                TextColumn::make('result')
                    ->badge()
                    ->formatStateUsing(function ($record) {
                        if (empty($record->result)) {
                            return 'Upcoming';
                        }
                        
                        return match($record->result) {
                            'fighter1_ko' => "{$record->fighter1->last_name} by KO/TKO",
                            'fighter2_ko' => "{$record->fighter2->last_name} by KO/TKO",
                            'fighter1_decision' => "{$record->fighter1->last_name} by Decision",
                            'fighter2_decision' => "{$record->fighter2->last_name} by Decision",
                            'fighter1_submission' => "{$record->fighter1->last_name} by Submission",
                            'fighter2_submission' => "{$record->fighter2->last_name} by Submission",
                            'draw' => 'Draw',
                            'no_contest' => 'No Contest',
                            'cancelled' => 'Cancelled',
                            default => 'Upcoming',
                        };
                    })
                    ->colors([
                        'secondary' => fn ($state): bool => empty($state),
                        'success' => fn ($state): bool => str_contains($state, 'fighter1_') || str_contains($state, 'fighter2_'),
                        'warning' => fn ($state): bool => $state === 'draw',
                        'danger' => fn ($state): bool => $state === 'no_contest' || $state === 'cancelled',
                    ]),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Event'),
                SelectFilter::make('weight_class')
                    ->options([
                        'Heavyweight' => 'Heavyweight',
                        'Cruiserweight' => 'Cruiserweight',
                        'Light Heavyweight' => 'Light Heavyweight',
                        'Super Middleweight' => 'Super Middleweight',
                        'Middleweight' => 'Middleweight',
                        'Welterweight' => 'Welterweight',
                        'Lightweight' => 'Lightweight',
                        'Featherweight' => 'Featherweight',
                        'Bantamweight' => 'Bantamweight',
                        'Flyweight' => 'Flyweight',
                    ]),
                SelectFilter::make('is_title_fight')
                    ->options([
                        '1' => 'Title Fights',
                        '0' => 'Non-title Fights',
                    ])
                    ->label('Title Fight'),
                SelectFilter::make('result')
                    ->options([
                        '' => 'Upcoming',
                        'fighter1_ko' => 'Fighter 1 by KO/TKO',
                        'fighter2_ko' => 'Fighter 2 by KO/TKO',
                        'fighter1_decision' => 'Fighter 1 by Decision',
                        'fighter2_decision' => 'Fighter 2 by Decision',
                        'fighter1_submission' => 'Fighter 1 by Submission',
                        'fighter2_submission' => 'Fighter 2 by Submission',
                        'draw' => 'Draw',
                        'no_contest' => 'No Contest',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Fight Result'),
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
            ->defaultSort('event.event_date', 'desc');
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
            'index' => Pages\ListFights::route('/'),
            'create' => Pages\CreateFight::route('/create'),
            'edit' => Pages\EditFight::route('/{record}/edit'),
        ];
    }    
}