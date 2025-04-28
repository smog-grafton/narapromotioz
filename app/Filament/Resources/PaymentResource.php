<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
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

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationGroup = 'Sales & Payments';
    
    protected static ?int $navigationSort = 2;

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
                        
                        Select::make('payable_type')
                            ->options([
                                'App\\Models\\Ticket' => 'Ticket',
                                'App\\Models\\Stream' => 'Stream',
                                'Other' => 'Other',
                            ])
                            ->required()
                            ->reactive(),
                        
                        Select::make('payable_id')
                            ->label('Related Item')
                            ->options(function (callable $get) {
                                $type = $get('payable_type');
                                
                                if (!$type || $type === 'Other') {
                                    return [];
                                }
                                
                                $model = new $type;
                                
                                // Query depends on the model type
                                if ($type === 'App\\Models\\Ticket') {
                                    return $model::pluck('ticket_number', 'id');
                                } elseif ($type === 'App\\Models\\Stream') {
                                    return $model::pluck('title', 'id');
                                }
                                
                                return [];
                            })
                            ->searchable()
                            ->preload(),
                        
                        TextInput::make('amount')
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
                        
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                        
                        TextInput::make('transaction_id')
                            ->maxLength(255)
                            ->helperText('External transaction reference ID'),
                        
                        DateTimePicker::make('paid_at')
                            ->label('Payment Date')
                            ->default(now()),
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
                TextColumn::make('id')
                    ->label('Payment #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payable_type')
                    ->label('Payment Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state)),
                TextColumn::make('payable_relation')
                    ->label('Related Item')
                    ->formatStateUsing(function ($record) {
                        if ($record->payable_type === 'App\\Models\\Ticket') {
                            return $record->payable->ticket_number ?? 'Unknown Ticket';
                        } elseif ($record->payable_type === 'App\\Models\\Stream') {
                            return $record->payable->title ?? 'Unknown Stream';
                        }
                        
                        return '-';
                    }),
                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->badge()
                    ->colors([
                        'success' => 'credit_card',
                        'warning' => 'pesapal',
                        'primary' => 'airtel_money',
                        'info' => 'mtn_money',
                        'gray' => ['cash', 'other'],
                    ]),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ]),
                TextColumn::make('paid_at')
                    ->dateTime('M d, Y • H:i')
                    ->sortable(),
                TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'pesapal' => 'PesaPal',
                        'airtel_money' => 'Airtel Money',
                        'mtn_money' => 'MTN Money',
                        'cash' => 'Cash',
                        'other' => 'Other',
                    ]),
                SelectFilter::make('payable_type')
                    ->options([
                        'App\\Models\\Ticket' => 'Ticket',
                        'App\\Models\\Stream' => 'Stream',
                        'Other' => 'Other',
                    ])
                    ->label('Payment Type'),
                Filter::make('paid_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('paid_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('paid_at', '<=', $date),
                            );
                    }),
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
            ->defaultSort('paid_at', 'desc');
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }    
}