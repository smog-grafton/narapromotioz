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

    protected static ?string $navigationLabel = 'Ticket Templates';
    
    protected static ?string $modelLabel = 'Ticket Template';
    
    protected static ?string $pluralModelLabel = 'Ticket Templates';

    protected static ?string $navigationGroup = 'Ticketing';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Template Design')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Template Image')
                            ->image()
                            ->directory('tickets/templates')
                            ->maxSize(5120),
                        Forms\Components\TextInput::make('width')
                            ->numeric()
                            ->default(800)
                            ->required(),
                        Forms\Components\TextInput::make('height')
                            ->numeric()
                            ->default(350)
                            ->required(),
                        Forms\Components\Select::make('ticket_type')
                            ->options([
                                'regular' => 'Regular',
                                'vip' => 'VIP',
                                'premium' => 'Premium',
                                'general' => 'General Admission',
                                'ringside' => 'Ringside',
                                'early_bird' => 'Early Bird',
                                'group' => 'Group Package',
                            ])
                            ->default('regular')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('QR Code Settings')
                    ->schema([
                        Forms\Components\KeyValue::make('qr_code_position')
                            ->label('QR Code Position')
                            ->keyLabel('Property')
                            ->valueLabel('Value')
                            ->default([
                                'x' => '0',
                                'y' => '0',
                                'width' => '150',
                                'height' => '150'
                            ]),
                        Forms\Components\KeyValue::make('text_fields')
                            ->label('Text Fields Configuration')
                            ->keyLabel('Field Name')
                            ->valueLabel('Configuration'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active templates can be used')
                            ->default(true),
                        Forms\Components\Toggle::make('is_default')
                            ->label('Default Template')
                            ->helperText('This will be the default template for this ticket type')
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ticket_type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Template Image')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('width')
                    ->label('Width (px)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('height')
                    ->label('Height (px)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tickets_count')
                    ->counts('tickets')
                    ->label('Tickets Using Template')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ticket_type')
                    ->options([
                        'regular' => 'Regular',
                        'vip' => 'VIP',
                        'premium' => 'Premium',
                        'general' => 'General Admission',
                        'ringside' => 'Ringside',
                        'early_bird' => 'Early Bird',
                        'group' => 'Group Package',
                    ]),
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