<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageSettings extends ManageRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save_settings')
                ->label('Save All Settings')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->action(function () {
                    // Save settings logic would go here
                    // This is just a placeholder for the real implementation
                    Notification::make()
                        ->title('Settings updated successfully')
                        ->success()
                        ->send();
                }),
        ];
    }
}