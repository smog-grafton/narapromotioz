<?php

namespace App\Filament\Admin\Resources\BoxerResource\Pages;

use App\Filament\Admin\Resources\BoxerResource;
use App\Models\Boxer;
use App\Models\FightRecord;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListFightRecords extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = BoxerResource::class;

    protected static string $view = 'filament.admin.resources.boxer-resource.pages.list-fight-records';

    public ?Boxer $record = null;

    public function mount(Boxer $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string
    {
        return "Fight Records for {$this->record->full_name}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FightRecord::query()->where('boxer_id', $this->record->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('opponent_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('venue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('result')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'win' => 'success',
                        'loss' => 'danger',
                        'draw' => 'warning',
                        'no_contest' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('round')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->options([
                        'win' => 'Win',
                        'loss' => 'Loss',
                        'draw' => 'Draw',
                        'no_contest' => 'No Contest',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Fight Record')
                    ->form([
                        Forms\Components\DatePicker::make('event_date')
                            ->required(),
                        Forms\Components\TextInput::make('opponent_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('venue')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('result')
                            ->options([
                                'win' => 'Win',
                                'loss' => 'Loss',
                                'draw' => 'Draw',
                                'no_contest' => 'No Contest',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('method')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('round')
                            ->numeric()
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Add Fight Record')
                    ->form([
                        Forms\Components\DatePicker::make('event_date')
                            ->required(),
                        Forms\Components\TextInput::make('opponent_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('venue')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('result')
                            ->options([
                                'win' => 'Win',
                                'loss' => 'Loss',
                                'draw' => 'Draw',
                                'no_contest' => 'No Contest',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('method')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('round')
                            ->numeric()
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->using(function (array $data): FightRecord {
                        $data['boxer_id'] = $this->record->id;
                        return FightRecord::create($data);
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Boxer')
                ->url(fn () => $this->getResource()::getUrl('view', ['record' => $this->record]))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
} 