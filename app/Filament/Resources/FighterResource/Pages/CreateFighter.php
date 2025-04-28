<?php

namespace App\Filament\Resources\FighterResource\Pages;

use App\Filament\Resources\FighterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFighter extends CreateRecord
{
    protected static string $resource = FighterResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}