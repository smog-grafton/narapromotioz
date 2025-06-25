<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventTicketResource\Pages;
use App\Filament\Admin\Resources\EventTicketResource\RelationManagers;
use App\Models\EventTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventTicketResource extends Resource
{
    protected static ?string $model = EventTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Event Tickets';
    
    protected static ?string $modelLabel = 'Event Ticket';
    
    protected static ?string $pluralModelLabel = 'Event Tickets';
    
    protected static ?string $navigationGroup = 'Ticketing';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ticket Information')
                    ->schema([
                        Forms\Components\Select::make('boxing_event_id')
                            ->relationship('event', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Event'),
                        Forms\Components\Select::make('ticket_template_id')
                            ->relationship('template', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Template (Optional)'),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pricing & Availability')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'KES' => 'KES (KSh)',
                                'GBP' => 'GBP (£)',
                                'EUR' => 'EUR (€)',
                            ])
                            ->default('USD')
                            ->required(),
                        Forms\Components\TextInput::make('quantity_available')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(100),
                        Forms\Components\TextInput::make('quantity_sold')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->helperText('This is automatically updated when tickets are sold'),
                        Forms\Components\TextInput::make('max_per_purchase')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(10)
                            ->label('Max Tickets Per Purchase'),
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
                            ->default('general')
                            ->required(),
                        Forms\Components\TextInput::make('seating_area')
                            ->maxLength(255)
                            ->label('Seating Area'),
                        Forms\Components\TagsInput::make('ticket_features')
                            ->label('Ticket Features')
                            ->placeholder('Add features...')
                            ->helperText('Press Enter to add each feature'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Sale Period')
                    ->schema([
                        Forms\Components\DateTimePicker::make('sale_start_date')
                            ->label('Sale Start Date')
                            ->helperText('Leave empty to start selling immediately'),
                        Forms\Components\DateTimePicker::make('sale_end_date')
                            ->label('Sale End Date')
                            ->after('sale_start_date')
                            ->helperText('Leave empty for no end date'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status & Settings')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'sold_out' => 'Sold Out',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Ticket')
                            ->helperText('Featured tickets will be highlighted'),
                        Forms\Components\Toggle::make('transferable')
                            ->label('Transferable')
                            ->default(true)
                            ->helperText('Allow ticket transfers to other people'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.name')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ticket_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'gray',
                        'vip' => 'warning',
                        'ringside' => 'success',
                        'premium' => 'info',
                        'early_bird' => 'primary',
                        'group' => 'secondary',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_available')
                    ->numeric()
                    ->sortable()
                    ->label('Available'),
                Tables\Columns\TextColumn::make('quantity_sold')
                    ->numeric()
                    ->sortable()
                    ->label('Sold'),
                Tables\Columns\TextColumn::make('remaining_quantity')
                    ->getStateUsing(fn ($record) => $record->remaining_quantity)
                    ->label('Remaining')
                    ->color(fn ($state) => $state <= 10 ? 'danger' : ($state <= 50 ? 'warning' : 'success'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('percentage_sold')
                    ->getStateUsing(fn ($record) => $record->percentage_sold . '%')
                    ->label('% Sold')
                    ->color(fn ($record) => $record->percentage_sold >= 90 ? 'danger' : ($record->percentage_sold >= 70 ? 'warning' : 'success')),
                Tables\Columns\TextColumn::make('seating_area')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'sold_out' => 'danger',
                        'suspended' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sale_start_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sale_end_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('boxing_event_id')
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'sold_out' => 'Sold Out',
                        'suspended' => 'Suspended',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
                Tables\Filters\Filter::make('on_sale')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('status', 'active')
                        ->where(function($q) {
                            $q->whereNull('sale_start_date')
                              ->orWhere('sale_start_date', '<=', now());
                        })
                        ->where(function($q) {
                            $q->whereNull('sale_end_date')
                              ->orWhere('sale_end_date', '>=', now());
                        })
                        ->whereRaw('quantity_available > quantity_sold')
                    )
                    ->label('Currently On Sale'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('toggle_status')
                        ->label(fn ($record) => $record->status === 'active' ? 'Deactivate' : 'Activate')
                        ->icon(fn ($record) => $record->status === 'active' ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn ($record) => $record->status === 'active' ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update([
                                'status' => $record->status === 'active' ? 'inactive' : 'active'
                            ]);
                        }),
                    Tables\Actions\Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function ($record) {
                            $newTicket = $record->replicate();
                            $newTicket->name = $record->name . ' (Copy)';
                            $newTicket->quantity_sold = 0;
                            $newTicket->save();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Builder $query) => $query->update(['status' => 'active']))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Builder $query) => $query->update(['status' => 'inactive']))
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
            'index' => Pages\ListEventTickets::route('/'),
            'create' => Pages\CreateEventTicket::route('/create'),
            'edit' => Pages\EditEventTicket::route('/{record}/edit'),
        ];
    }
}
