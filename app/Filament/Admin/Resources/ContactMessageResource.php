<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactMessageResource\Pages;
use App\Filament\Admin\Resources\ContactMessageResource\RelationManagers;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationLabel = 'Contact Messages';
    
    protected static ?string $navigationGroup = 'Communications';
    
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::unread()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $unreadCount = static::getModel()::unread()->count();
        return $unreadCount > 0 ? 'warning' : 'gray';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->columnSpan(1),
                        TextInput::make('subject')
                            ->maxLength(255)
                            ->columnSpan(1),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Message')
                    ->schema([
                        Textarea::make('message')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Status & Notes')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'unread' => 'Unread',
                                'read' => 'Read',
                                'replied' => 'Replied',
                            ])
                            ->default('unread')
                            ->required()
                            ->columnSpan(1),
                        DateTimePicker::make('read_at')
                            ->label('Read At')
                            ->columnSpan(1),
                        DateTimePicker::make('replied_at')
                            ->label('Replied At')
                            ->columnSpan(1),
                        Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),
                TextColumn::make('subject')
                    ->searchable()
                    ->toggleable()
                    ->limit(30)
                    ->placeholder('—'),
                TextColumn::make('message')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'unread',
                        'warning' => 'read',
                        'success' => 'replied',
                    ])
                    ->icons([
                        'heroicon-o-envelope' => 'unread',
                        'heroicon-o-envelope-open' => 'read',
                        'heroicon-o-check-circle' => 'replied',
                    ]),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('read_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'unread' => 'Unread',
                        'read' => 'Read',
                        'replied' => 'Replied',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('markAsRead')
                    ->icon('heroicon-o-envelope-open')
                    ->color('warning')
                    ->action(function (ContactMessage $record) {
                        $record->markAsRead();
                        Notification::make()
                            ->title('Message marked as read')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (ContactMessage $record) => $record->status === 'unread'),
                Tables\Actions\Action::make('markAsReplied')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (ContactMessage $record) {
                        $record->markAsReplied();
                        Notification::make()
                            ->title('Message marked as replied')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (ContactMessage $record) => $record->status !== 'replied'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-envelope-open')
                        ->action(function (Collection $records) {
                            $records->each->markAsRead();
                            Notification::make()
                                ->title('Messages marked as read')
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('markAsReplied')
                        ->label('Mark as Replied')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Collection $records) {
                            $records->each->markAsReplied();
                            Notification::make()
                                ->title('Messages marked as replied')
                                ->success()
                                ->send();
                        }),
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
            'index' => Pages\ListContactMessages::route('/'),
            'create' => Pages\CreateContactMessage::route('/create'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
