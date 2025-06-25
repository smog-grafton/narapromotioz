<?php

namespace App\Filament\Admin\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\ContactMessage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class LatestContactMessages extends BaseWidget
{
    protected static ?string $heading = 'Latest Contact Messages';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContactMessage::query()
                    ->where('status', 'unread')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->icon('heroicon-m-envelope'),
                    
                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (ContactMessage $record): ?string {
                        return $record->subject;
                    }),
                    
                TextColumn::make('message')
                    ->label('Message')
                    ->limit(60)
                    ->tooltip(function (ContactMessage $record): ?string {
                        return $record->message;
                    }),
                    
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'unread',
                        'warning' => 'read',
                        'success' => 'replied',
                    ])
                    ->icons([
                        'heroicon-m-exclamation-triangle' => 'unread',
                        'heroicon-m-eye' => 'read',
                        'heroicon-m-check-circle' => 'replied',
                    ]),
                    
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->since()
                    ->tooltip(function (ContactMessage $record): string {
                        return $record->created_at->format('F j, Y \a\t g:i A');
                    }),
            ])
            ->actions([
                Action::make('mark_read')
                    ->label('Mark as Read')
                    ->icon('heroicon-m-eye')
                    ->color('warning')
                    ->visible(fn (ContactMessage $record): bool => $record->status === 'unread')
                    ->action(function (ContactMessage $record) {
                        $record->markAsRead();
                        $this->dispatch('$refresh');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark message as read')
                    ->modalDescription('Are you sure you want to mark this message as read?'),
                    
                Action::make('mark_replied')
                    ->label('Mark as Replied')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (ContactMessage $record): bool => $record->status === 'read')
                    ->action(function (ContactMessage $record) {
                        $record->markAsReplied();
                        $this->dispatch('$refresh');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark message as replied')
                    ->modalDescription('Are you sure you want to mark this message as replied?'),
                    
                Action::make('view')
                    ->label('View Full Message')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->modalContent(function (ContactMessage $record) {
                        return view('filament.widgets.contact-message-modal', ['record' => $record]);
                    })
                    ->modalHeading(fn (ContactMessage $record): string => 'Message from ' . $record->name)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->emptyStateHeading('No unread messages')
            ->emptyStateDescription('All contact messages have been read!')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
