<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['email_verified'] = ! is_null($data['email_verified_at']);

        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['email_verified'] && is_null($data['email_verified_at'])) {
            $data['email_verified_at'] = now();
        }
        
        if (! $data['email_verified']) {
            $data['email_verified_at'] = null;
        }
        
        unset($data['email_verified']);
        
        return $data;
    }
}