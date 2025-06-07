<?php

namespace App\Filament\Admin\Resources\NewsTagResource\Pages;

use App\Filament\Admin\Resources\NewsTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsTags extends ListRecords
{
    protected static string $resource = NewsTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
