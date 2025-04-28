<?php

namespace App\Filament\Resources\FighterResource\Pages;

use App\Filament\Resources\FighterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFighters extends ListRecords
{
    protected static string $resource = FighterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}