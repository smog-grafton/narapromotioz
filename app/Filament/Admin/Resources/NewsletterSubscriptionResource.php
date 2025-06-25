<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NewsletterSubscriptionResource\Pages;
use App\Models\NewsletterSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class NewsletterSubscriptionResource extends Resource
{
    protected static ?string $model = NewsletterSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationLabel = 'Newsletter Subscriptions';
    
    protected static ?string $navigationGroup = 'Communications';
    
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $activeCount = static::getModel()::active()->count();
        return $activeCount > 0 ? 'success' : 'gray';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Subscription Information')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'unsubscribed' => 'Unsubscribed',
                                'bounced' => 'Bounced',
                            ])
                            ->default('active')
                            ->required()
                            ->columnSpan(1),
                        Select::make('source')
                            ->options([
                                'website' => 'Website',
                                'admin' => 'Admin',
                                'api' => 'API',
                                'import' => 'Import',
                            ])
                            ->default('website')
                            ->required()
                            ->columnSpan(1),
                        DateTimePicker::make('subscribed_at')
                            ->default(now())
                            ->required()
                            ->columnSpan(1),
                        DateTimePicker::make('unsubscribed_at')
                            ->columnSpan(1),
                        TextInput::make('unsubscribe_token')
                            ->maxLength(255)
                            ->disabled()
                            ->columnSpan(1),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'unsubscribed',
                        'warning' => 'bounced',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'active',
                        'heroicon-o-x-circle' => 'unsubscribed',
                        'heroicon-o-exclamation-triangle' => 'bounced',
                    ]),
                TextColumn::make('source')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('unsubscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'unsubscribed' => 'Unsubscribed',
                        'bounced' => 'Bounced',
                    ]),
                SelectFilter::make('source')
                    ->options([
                        'website' => 'Website',
                        'admin' => 'Admin',
                        'api' => 'API',
                        'import' => 'Import',
                    ]),
                Tables\Filters\Filter::make('subscribed_at')
                    ->form([
                        Forms\Components\DatePicker::make('subscribed_from'),
                        Forms\Components\DatePicker::make('subscribed_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['subscribed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('subscribed_at', '>=', $date),
                            )
                            ->when(
                                $data['subscribed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('subscribed_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('unsubscribe')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (NewsletterSubscription $record) {
                        $record->unsubscribe();
                        Notification::make()
                            ->title('Subscription cancelled')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (NewsletterSubscription $record) => $record->status === 'active'),
                Tables\Actions\Action::make('resubscribe')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (NewsletterSubscription $record) {
                        $record->resubscribe();
                        Notification::make()
                            ->title('Subscription reactivated')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (NewsletterSubscription $record) => $record->status !== 'active'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('unsubscribe')
                        ->label('Unsubscribe')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->each->unsubscribe();
                            Notification::make()
                                ->title('Subscriptions cancelled')
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('resubscribe')
                        ->label('Resubscribe')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each->resubscribe();
                            Notification::make()
                                ->title('Subscriptions reactivated')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('subscribed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscriptions::route('/'),
            'create' => Pages\CreateNewsletterSubscription::route('/create'),
            'edit' => Pages\EditNewsletterSubscription::route('/{record}/edit'),
        ];
    }
}
