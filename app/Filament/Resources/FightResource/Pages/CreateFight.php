<?php

namespace App\Filament\Resources\FightResource\Pages;

use App\Filament\Resources\FightResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFight extends CreateRecord
{
    protected static string $resource = FightResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}