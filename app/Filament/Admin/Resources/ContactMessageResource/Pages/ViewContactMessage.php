<?php

namespace App\Filament\Admin\Resources\ContactMessageResource\Pages;

use App\Filament\Admin\Resources\ContactMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('markAsRead')
                ->icon('heroicon-o-envelope-open')
                ->color('warning')
                ->action(function () {
                    $this->record->markAsRead();
                    $this->refreshFormData(['status', 'read_at']);
                })
                ->visible(fn () => $this->record->status === 'unread'),
            Actions\Action::make('markAsReplied')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->markAsReplied();
                    $this->refreshFormData(['status', 'replied_at']);
                })
                ->visible(fn () => $this->record->status !== 'replied'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mark as read when viewing
        if ($this->record->status === 'unread') {
            $this->record->markAsRead();
        }
        
        return $data;
    }
} 