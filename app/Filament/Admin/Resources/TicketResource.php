<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TicketResource\Pages;
use App\Models\TicketTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = TicketTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Ticketing';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ticket Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('quantity_available')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\TextInput::make('max_per_purchase')
                            ->numeric()
                            ->minValue(1)
                            ->default(10),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ticket Details')
                    ->schema([
                        Forms\Components\Select::make('ticket_type')
                            ->options([
                                'general' => 'General Admission',
                                'vip' => 'VIP',
                                'ringside' => 'Ringside',
                                'premium' => 'Premium',
                                'early_bird' => 'Early Bird',
                                'group' => 'Group Package',
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('sale_starts_at')
                            ->required(),
                        Forms\Components\DateTimePicker::make('sale_ends_at')
                            ->required()
                            ->after('sale_starts_at'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ticket Design')
                    ->schema([
                        Forms\Components\FileUpload::make('template_image')
                            ->image()
                            ->directory('tickets/templates')
                            ->maxSize(5120),
                        Forms\Components\ColorPicker::make('background_color')
                            ->default('#FFFFFF'),
                        Forms\Components\ColorPicker::make('text_color')
                            ->default('#000000'),
                        Forms\Components\Toggle::make('include_qr_code')
                            ->default(true),
                        Forms\Components\Toggle::make('include_event_logo')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active tickets can be purchased')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Featured tickets will be highlighted on the event page')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ticket_type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_available')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tickets_sold')
                    ->getStateUsing(fn ($record) => $record->purchases()->count())
                    ->label('Tickets Sold'),
                Tables\Columns\TextColumn::make('sale_starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_ends_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Event'),
                Tables\Filters\SelectFilter::make('ticket_type')
                    ->options([
                        'general' => 'General Admission',
                        'vip' => 'VIP',
                        'ringside' => 'Ringside',
                        'premium' => 'Premium',
                        'early_bird' => 'Early Bird',
                        'group' => 'Group Package',
                    ]),
                Tables\Filters\Filter::make('on_sale')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('sale_starts_at', '<=', now())
                        ->where('sale_ends_at', '>=', now())
                        ->where('is_active', true)
                    )
                    ->label('Currently On Sale'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (TicketTemplate $record) {
                            $newTicket = $record->replicate();
                            $newTicket->name = $record->name . ' (Copy)';
                            $newTicket->save();
                        }),
                    Tables\Actions\Action::make('toggleActive')
                        ->label(fn (TicketTemplate $record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (TicketTemplate $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->action(function (TicketTemplate $record) {
                            $record->update(['is_active' => !$record->is_active]);
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activateTickets')
                        ->label('Activate Tickets')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivateTickets')
                        ->label('Deactivate Tickets')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
} 