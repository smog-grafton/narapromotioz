<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationGroup = 'Sales & Payments';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Customer'),
                        
                        Select::make('event_id')
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $event = Event::find($state);
                                    if ($event) {
                                        $set('amount_paid', $event->ticket_price);
                                    }
                                }
                            }),
                        
                        TextInput::make('ticket_number')
                            ->required()
                            ->maxLength(50)
                            ->default(fn() => 'TKT-' . strtoupper(substr(md5(uniqid()), 0, 8)))
                            ->label('Ticket Number')
                            ->helperText('Auto-generated ticket number'),
                        
                        TextInput::make('amount_paid')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->required(),
                        
                        Select::make('payment_method')
                            ->options([
                                'credit_card' => 'Credit Card',
                                'pesapal' => 'PesaPal',
                                'airtel_money' => 'Airtel Money',
                                'mtn_money' => 'MTN Money',
                                'cash' => 'Cash',
                                'other' => 'Other',
                            ])
                            ->required(),
                        
                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                        
                        Select::make('ticket_type')
                            ->options([
                                'standard' => 'Standard',
                                'vip' => 'VIP',
                                'ringside' => 'Ringside',
                                'premium' => 'Premium',
                            ])
                            ->default('standard')
                            ->required(),
                        
                        TextInput::make('payment_reference')
                            ->maxLength(255)
                            ->label('Payment Reference ID'),
                    ]),
                
                Textarea::make('notes')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')
                    ->label('Ticket #')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('event.event_date')
                    ->label('Event Date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('amount_paid')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('ticket_type')
                    ->badge()
                    ->colors([
                        'gray' => 'standard',
                        'warning' => 'vip',
                        'danger' => 'ringside',
                        'purple' => 'premium',
                    ]),
                TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ]),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y • H:i')
                    ->label('Purchase Date')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                SelectFilter::make('ticket_type')
                    ->options([
                        'standard' => 'Standard',
                        'vip' => 'VIP',
                        'ringside' => 'Ringside',
                        'premium' => 'Premium',
                    ]),
                SelectFilter::make('event_id')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Event'),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_ticket')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Ticket $record): string => route('tickets.download', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }    
}